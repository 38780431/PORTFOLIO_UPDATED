const express = require('express');
const path = require('path');
const fs = require('fs');
const initSqlJs = require('sql.js');
const session = require('express-session');
const bcrypt = require('bcryptjs');
const crypto = require('crypto');
const nodemailer = require('nodemailer');

const app = express();
const PORT = process.env.PORT || 3000;
const dataFolder = path.join(__dirname, 'data');
const dbPath = path.join(dataFolder, 'app.db');
const sessionSecret = process.env.SESSION_SECRET;
const smtpPort = Number(process.env.SMTP_PORT);
const mailTransporter = process.env.SMTP_HOST && process.env.SMTP_USER
  && process.env.SMTP_PASSWORD && process.env.MAIL_FROM
  && Number.isInteger(smtpPort) && smtpPort > 0
  ? nodemailer.createTransport({
    host: process.env.SMTP_HOST,
    port: smtpPort,
    secure: process.env.SMTP_SECURE === 'true',
    auth: {
      user: process.env.SMTP_USER,
      pass: process.env.SMTP_PASSWORD,
    },
  })
  : null;

let db;
let SQL;

if (process.env.NODE_ENV === 'production') {
  app.set('trust proxy', 1);
}

if (!sessionSecret || Buffer.byteLength(sessionSecret) < 32) {
  throw new Error('SESSION_SECRET must be set to a random value of at least 32 bytes.');
}

function saveDb() {
  const data = db.export();
  fs.writeFileSync(dbPath, Buffer.from(data));
}

function dbPrepare(sql, params = []) {
  const stmt = db.prepare(sql);
  stmt.bind(params);
  return stmt;
}

function dbGet(sql, params = []) {
  const stmt = dbPrepare(sql, params);
  const row = stmt.step() ? stmt.getAsObject() : null;
  stmt.free();
  return row;
}

function dbRun(sql, params = []) {
  const stmt = dbPrepare(sql, params);
  stmt.step();
  stmt.free();
  saveDb();
}

async function initDatabase() {
  if (!fs.existsSync(dataFolder)) {
    fs.mkdirSync(dataFolder, { recursive: true });
  }

  SQL = await initSqlJs({ locateFile: (file) => path.join(__dirname, 'node_modules', 'sql.js', 'dist', file) });

  if (fs.existsSync(dbPath)) {
    const fileBuffer = fs.readFileSync(dbPath);
    db = new SQL.Database(new Uint8Array(fileBuffer));
  } else {
    db = new SQL.Database();
  }

  db.run(`
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      first_name TEXT NOT NULL,
      last_name TEXT NOT NULL,
      email TEXT NOT NULL UNIQUE,
      password TEXT NOT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
  `);
  db.run(`
    CREATE TABLE IF NOT EXISTS password_reset_tokens (
      token_hash TEXT PRIMARY KEY,
      user_id INTEGER NOT NULL,
      expires_at INTEGER NOT NULL,
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
  `);
  db.run('CREATE INDEX IF NOT EXISTS password_reset_tokens_user_id ON password_reset_tokens(user_id)');
  saveDb();
}

app.use(express.urlencoded({ extended: false }));
app.use(express.json());
app.use(
  session({
    secret: sessionSecret,
    resave: false,
    saveUninitialized: false,
    cookie: {
      httpOnly: true,
      sameSite: 'lax',
      secure: process.env.NODE_ENV === 'production',
      maxAge: 24 * 60 * 60 * 1000,
    },
  })
);

// Set MIME types
express.static.mime.types['webmanifest'] = 'application/manifest+json';
express.static.mime.types['json'] = 'application/json';

app.use(express.static(path.join(__dirname, 'public'), {
  setHeaders: (res, path) => {
    if (path.endsWith('manifest.json')) {
      res.setHeader('Content-Type', 'application/manifest+json');
    } else if (path.endsWith('service-worker.js')) {
      res.setHeader('Content-Type', 'application/javascript');
      res.setHeader('Service-Worker-Allowed', '/');
    }
  }
}));

function requireLogin(req, res, next) {
  if (req.session && req.session.userId) {
    return next();
  }
  res.redirect('/');
}

function getFormString(value) {
  return typeof value === 'string' ? value : '';
}

app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.get('/dashboard', requireLogin, (req, res) => {
  res.sendFile(path.join(__dirname, 'views', 'dashboard.html'));
});

app.get('/profile', requireLogin, (req, res) => {
  const userId = req.session.userId;
  const row = dbGet('SELECT first_name, last_name, email FROM users WHERE id = ?', [userId]);
  if (!row) {
    return res.status(404).json({ error: 'User not found.' });
  }
  res.json({ firstName: row.first_name, lastName: row.last_name, email: row.email });
});

// Email validation regex
function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

app.post('/register', (req, res) => {
  const firstName = getFormString(req.body.fname).trim();
  const lastName = getFormString(req.body.lname).trim();
  const email = getFormString(req.body.email).trim().toLowerCase();
  const password = getFormString(req.body.password);

  if (!firstName || !lastName || !email || !password) {
    return res.redirect('/?action=register&error=missing_fields');
  }

  if (!isValidEmail(email)) {
    return res.redirect('/?action=register&error=invalid_email');
  }

  if (password.length < 8) {
    return res.redirect('/?action=register&error=password_short');
  }

  const passwordHash = bcrypt.hashSync(password, 10);

  try {
    dbRun('INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)', [firstName, lastName, email, passwordHash]);
    res.redirect('/?action=login&registered=1');
  } catch (error) {
    const message = error && error.message ? error.message : '';
    if (message.includes('UNIQUE constraint failed')) {
      return res.redirect('/?action=register&error=email_exists');
    }
    console.error('Registration error:', error);
    return res.redirect('/?action=register&error=server_error');
  }
});

app.post('/login', (req, res) => {
  const email = getFormString(req.body.email).trim().toLowerCase();
  const password = getFormString(req.body.password);

  if (!email || !password) {
    return res.redirect('/?action=login&error=invalid_credentials');
  }

  if (!isValidEmail(email)) {
    return res.redirect('/?action=login&error=invalid_email');
  }

  const user = dbGet('SELECT * FROM users WHERE email = ?', [email]);
  if (!user || !bcrypt.compareSync(password, user.password)) {
    return res.redirect('/?action=login&error=invalid_credentials');
  }

  req.session.regenerate((error) => {
    if (error) {
      console.error('Failed to regenerate login session:', error);
      return res.redirect('/?action=login&error=server_error');
    }

    req.session.userId = user.id;
    req.session.userName = `${user.first_name} ${user.last_name}`;
    req.session.save((saveError) => {
      if (saveError) {
        console.error('Failed to save login session:', saveError);
        return res.redirect('/?action=login&error=server_error');
      }
      res.redirect('/dashboard');
    });
  });
});

app.post('/forgot-password', async (req, res) => {
  const email = getFormString(req.body.email).trim().toLowerCase();

  if (!email) {
    return res.redirect('/?action=forgot&error=missing_fields');
  }

  if (!isValidEmail(email)) {
    return res.redirect('/?action=forgot&error=invalid_email');
  }

  if (!mailTransporter) {
    return res.redirect('/?action=forgot&error=mail_unavailable');
  }

  let user;
  let token;
  let tokenHash;
  let resetUrl;
  const expiresAt = Date.now() + 30 * 60 * 1000;
  try {
    user = dbGet('SELECT id, email FROM users WHERE email = ?', [email]);
  } catch (error) {
    console.error('Failed to look up password reset account:', error);
    return res.redirect('/?action=forgot&error=server_error');
  }

  if (!user) {
    return res.redirect('/?action=forgot&resetRequested=1');
  }

  try {
    token = crypto.randomBytes(32).toString('hex');
    tokenHash = crypto.createHash('sha256').update(token).digest('hex');
    resetUrl = new URL('/reset-password', process.env.APP_BASE_URL || `http://localhost:${PORT}`);
    resetUrl.searchParams.set('token', token);
    dbRun('DELETE FROM password_reset_tokens WHERE user_id = ?', [user.id]);
    dbRun('INSERT INTO password_reset_tokens (token_hash, user_id, expires_at) VALUES (?, ?, ?)', [tokenHash, user.id, expiresAt]);
  } catch (error) {
    console.error('Failed to create password reset token:', error);
    return res.redirect('/?action=forgot&error=server_error');
  }

  try {
    await mailTransporter.sendMail({
      from: process.env.MAIL_FROM,
      to: user.email,
      subject: 'Reset your portfolio account password',
      text: `Use this one-time link to reset your password within 30 minutes:\n\n${resetUrl.toString()}`,
      html: `<p>Use this one-time link to reset your password within 30 minutes:</p><p><a href="${resetUrl.toString()}">Reset password</a></p>`,
    });
  } catch (error) {
    try {
      dbRun('DELETE FROM password_reset_tokens WHERE token_hash = ?', [tokenHash]);
    } catch (cleanupError) {
      console.error('Failed to remove an undelivered password reset token:', cleanupError);
    }
    console.error('Password reset email delivery failed:', error);
    return res.redirect('/?action=forgot&error=mail_unavailable');
  }

  res.redirect('/?action=forgot&resetRequested=1');
});

app.post('/reset-password', (req, res) => {
  const token = typeof req.body.token === 'string' ? req.body.token : '';
  const newPassword = typeof req.body.newPassword === 'string' ? req.body.newPassword : '';

  if (!token || !newPassword) {
    return res.redirect('/?action=reset&error=missing_fields');
  }

  if (newPassword.length < 8) {
    return res.redirect(`/?action=reset&token=${encodeURIComponent(token)}&error=password_short`);
  }

  const tokenHash = crypto.createHash('sha256').update(token).digest('hex');
  const resetToken = dbGet(
    'SELECT user_id FROM password_reset_tokens WHERE token_hash = ? AND expires_at > ?',
    [tokenHash, Date.now()]
  );
  if (!resetToken) {
    return res.redirect('/?action=reset&error=reset_invalid');
  }

  const passwordHash = bcrypt.hashSync(newPassword, 10);
  let transactionStarted = false;
  try {
    db.run('BEGIN IMMEDIATE TRANSACTION');
    transactionStarted = true;
    db.run('UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', [passwordHash, resetToken.user_id]);
    db.run('DELETE FROM password_reset_tokens WHERE token_hash = ?', [tokenHash]);
    db.run('COMMIT');
    transactionStarted = false;
    saveDb();
  } catch (error) {
    if (transactionStarted) {
      db.run('ROLLBACK');
    }
    console.error('Password reset failed:', error);
    return res.redirect('/?action=reset&token=' + encodeURIComponent(token) + '&error=server_error');
  }

  res.redirect('/?action=login&passwordReset=1');
});

app.get('/logout', (req, res) => {
  req.session.destroy((error) => {
    if (error) {
      console.error('Logout failed:', error);
      return res.status(500).send('Logout failed. Please try again.');
    }
    res.redirect('/?loggedout=1');
  });
});

app.get('/forgot-password', (req, res) => {
  res.set('Cache-Control', 'no-store');
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.get('/reset-password', (req, res) => {
  res.set({
    'Cache-Control': 'no-store',
    'Referrer-Policy': 'no-referrer',
    'X-Robots-Tag': 'noindex',
  });
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

initDatabase()
  .then(() => {
    app.listen(PORT, () => {
      console.log(`Server is running at http://localhost:${PORT}`);
    });
  })
  .catch((err) => {
    console.error('Failed to initialize database:', err);
    process.exit(1);
  });

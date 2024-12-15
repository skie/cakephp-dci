-- Test database schema.
--
-- If you are not using CakePHP migrations you can put
-- your application's schema in this file and use it in tests.

-- Accounts table
CREATE TABLE accounts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    balance FLOAT DEFAULT 0 NOT NULL
);

-- Complex Accounts table
CREATE TABLE complex_accounts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    balance DECIMAL(10,2) DEFAULT 0 NOT NULL,
    account_type VARCHAR(50) DEFAULT 'standard' NOT NULL,
    status VARCHAR(20) DEFAULT 'active' NOT NULL,
    is_frozen BOOLEAN DEFAULT 0 NOT NULL,
    created DATETIME,
    modified DATETIME
);
CREATE INDEX idx_complex_accounts_type_status ON complex_accounts(account_type, status);

-- Audit Logs table
CREATE TABLE audit_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    model VARCHAR(100) NOT NULL,
    foreign_key INTEGER NOT NULL,
    operation VARCHAR(50) NOT NULL,
    data JSON,
    created DATETIME
);
CREATE INDEX idx_audit_logs_model_key ON audit_logs(model, foreign_key);
CREATE INDEX idx_audit_logs_operation ON audit_logs(operation);
CREATE INDEX idx_audit_logs_created ON audit_logs(created);

-- Rooms table
CREATE TABLE rooms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    number VARCHAR(10) NOT NULL,
    type VARCHAR(50) NOT NULL,
    capacity INTEGER NOT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'available',
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL,
    UNIQUE (number)
);

-- Guests table
CREATE TABLE guests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    loyalty_level VARCHAR(20) DEFAULT 'standard',
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL,
    UNIQUE (email)
);

-- Reservations table
CREATE TABLE reservations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    room_id INTEGER NOT NULL,
    primary_guest_id INTEGER NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    total_price DECIMAL(10,2) NOT NULL,
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL,
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (primary_guest_id) REFERENCES guests(id)
);

-- Reservation Guests table
CREATE TABLE reservation_guests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reservation_id INTEGER NOT NULL,
    guest_id INTEGER NOT NULL,
    created DATETIME NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id),
    FOREIGN KEY (guest_id) REFERENCES guests(id)
);

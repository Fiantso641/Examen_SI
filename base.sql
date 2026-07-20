

CREATE TABLE IF NOT EXISTS operator_config (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    config_key VARCHAR(50) UNIQUE NOT NULL,
    config_value TEXT NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO operator_config (config_key, config_value, description) VALUES
('transfer_external_surcharge_percent', '0.00', 'Pourcentage additionnel pour les transferts vers les autres opérateurs');

CREATE TABLE IF NOT EXISTS valid_prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix VARCHAR(10) NOT NULL UNIQUE,
    operator_name VARCHAR(100),
    status VARCHAR(20) DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS operation_types (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS fee_schedules (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    operation_type_id INTEGER NOT NULL,
    min_amount DECIMAL(15,2) NOT NULL,
    max_amount DECIMAL(15,2) NOT NULL,
    fee_amount DECIMAL(15,2) NOT NULL,
    fee_percentage DECIMAL(5,2) DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operation_type_id) REFERENCES operation_types(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    phone_number VARCHAR(20) UNIQUE NOT NULL,
    pin VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    balance DECIMAL(15,2) DEFAULT 0.00,
    status VARCHAR(20) DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_id VARCHAR(30) UNIQUE NOT NULL,
    operation_type_id INTEGER NOT NULL,
    client_id INTEGER NOT NULL,
    recipient_phone VARCHAR(20),
    amount DECIMAL(15,2) NOT NULL,
    fee DECIMAL(15,2) DEFAULT 0.00,
    balance_before DECIMAL(15,2) NOT NULL,
    balance_after DECIMAL(15,2) NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'completed',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operation_type_id) REFERENCES operation_types(id),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);


INSERT INTO valid_prefixes (prefix, operator_name, status) VALUES
('033', 'airtel', 'active'),
('037', 'Orange', 'active');

INSERT INTO operation_types (code, name, description, status) VALUES
('DEPOSIT', 'Dépôt', 'Dépôt d''argent sur le compte', 'active'),
('WITHDRAWAL', 'Retrait', 'Retrait d''argent du compte', 'active'),
('TRANSFER', 'Transfert', 'Transfert d''argent vers un autre client', 'active');

INSERT INTO fee_schedules (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) VALUES
(3, 0, 1000, 100, 0.00),
(3, 1001, 5000, 200, 0.00),
(3, 5001, 10000, 400, 0.00),
(3, 10001, 50000, 800, 0.00),
(3, 50001, 999999999, 0, 1.00);

INSERT INTO fee_schedules (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) VALUES
(2, 0, 1000, 100, 0.00),
(2, 1001, 5000, 200, 0.00),
(2, 5001, 10000, 400, 0.00),
(2, 10001, 50000, 800, 0.00),
(2, 50001, 999999999, 0, 1.50);

INSERT INTO fee_schedules (operation_type_id, min_amount, max_amount, fee_amount, fee_percentage) VALUES
(1, 0, 999999999, 0, 0.00);

INSERT INTO clients (phone_number, pin, full_name, balance, status) VALUES
('0333537214', '1234', 'Fiantso', 500000.00, 'active'),
('0370254689', '1234', 'Toky', 500000.00, 'active');

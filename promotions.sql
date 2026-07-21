CREATE TABLE IF NOT EXISTS promotions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    operator_name VARCHAR(100) NOT NULL,
    discount_percentage DECIMAL(5,2) NOT NULL,
    description TEXT,
    start_date DATETIME,
    end_date DATETIME,
    status VARCHAR(20) DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

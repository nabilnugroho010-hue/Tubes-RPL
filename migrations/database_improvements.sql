-- Migration: Database Improvements for SPGFood
-- Date: 2026-08-06
-- Purpose: Improve database structure for better performance and data consistency

-- 1. Change tgl_pesanan from DATE to DATETIME for precise timestamp
ALTER TABLE data_pesanan MODIFY COLUMN tgl_pesanan DATETIME;

-- 2. Change kode_pelanggan from INT to VARCHAR to support format "CUST-XXXX"
ALTER TABLE data_pesanan MODIFY COLUMN kode_pelanggan VARCHAR(50);

-- 3. Add metode_pembayaran column for single source of truth
ALTER TABLE data_pesanan ADD COLUMN metode_pembayaran VARCHAR(50) AFTER status;

-- 4. Add indexes for frequently queried columns
CREATE INDEX idx_tgl_pesanan ON data_pesanan(tgl_pesanan);
CREATE INDEX idx_status ON data_pesanan(status);
CREATE INDEX idx_kode_pelanggan ON data_pesanan(kode_pelanggan);

-- 5. Drop unused tables
DROP TABLE IF EXISTS data_detail_pesanan;
DROP TABLE IF EXISTS data_status_pesanan;

-- Verification Queries
-- Check current structure
DESCRIBE data_pesanan;
DESCRIBE data_menu;
DESCRIBE rincian_pesanan;
DESCRIBE data_pembayaran;

-- Check indexes
SHOW INDEX FROM data_pesanan;

-- Check remaining tables
SHOW TABLES;

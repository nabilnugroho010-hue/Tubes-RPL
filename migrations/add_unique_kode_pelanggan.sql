-- Migration: Add Unique Constraint to kode_pelanggan
-- Purpose: Prevent duplicate customer codes
-- Date: 2026-08-06

-- Add unique index on kode_pelanggan column
ALTER TABLE data_pesanan ADD UNIQUE INDEX idx_kode_pelanggan (kode_pelanggan);

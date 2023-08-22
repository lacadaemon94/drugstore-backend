-- Alter Inventario Table to seet id to default auto generate uuid (must run this again on every migration)
ALTER TABLE inventario
ALTER COLUMN id SET DEFAULT uuid_generate_v4();

-- Trigger-Function to Aggregate Producto if it exists in inventario tipo = new and producto = new and expiracion = new
CREATE OR REPLACE FUNCTION update_cantidad_if_exists()
RETURNS TRIGGER AS $$
BEGIN
    -- Check if a row with the same producto_id_id and expiracion already exists
    IF EXISTS (SELECT 1 FROM inventario WHERE tipo_id_id = NEW.tipo_id_id AND producto_id_id = NEW.producto_id_id AND expiracion = NEW.expiracion) THEN
        -- Update the cantidad of the existing row
        UPDATE inventario
        SET cantidad = cantidad + NEW.cantidad
        WHERE tipo_id_id = NEW.tipo_id_id AND producto_id_id = NEW.producto_id_id AND expiracion = NEW.expiracion;

        -- Prevent the insertion of the new row
        RETURN NULL;
    END IF;

    -- If no such row exists, allow the insertion to proceed
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER tr_update_cantidad_if_exists
BEFORE INSERT ON inventario
FOR EACH ROW
EXECUTE FUNCTION update_cantidad_if_exists();

-- Trigger-Function to handle transferencia inserts, it checks the origen and destino tipo_id_id and inserts, deletes or updates based on conditions met
CREATE OR REPLACE FUNCTION handle_transferencia_insert()
RETURNS TRIGGER AS $$
DECLARE
    affected_rows INTEGER;
BEGIN
    -- Deduct the cantidad from the origen tipo_id_id
    UPDATE inventario
    SET cantidad = cantidad - NEW.cantidad
    WHERE producto_id_id = NEW.producto_id_id AND expiracion = NEW.expiracion AND tipo_id_id = NEW.origen_id;

    -- Check the number of affected rows
    GET DIAGNOSTICS affected_rows = ROW_COUNT;

    -- If no rows were affected, raise an exception to prevent the insert
    IF affected_rows = 0 THEN
        RAISE EXCEPTION 'No rows affected in origen. Insertion in transferencia table prevented.';
    END IF;

    -- Delete the entry if the quantity reaches 0
    DELETE FROM inventario
    WHERE producto_id_id = NEW.producto_id_id AND expiracion = NEW.expiracion AND tipo_id_id = NEW.origen_id AND cantidad <= 0;

    -- Check if an entry with the same producto_id_id, expiracion, and destino tipo_id_id already exists
    IF EXISTS (SELECT 1 FROM inventario WHERE producto_id_id = NEW.producto_id_id AND expiracion = NEW.expiracion AND tipo_id_id = NEW.destino_id) THEN
        -- Aggregate the cantidad
        UPDATE inventario
        SET cantidad = cantidad + NEW.cantidad
        WHERE producto_id_id = NEW.producto_id_id AND expiracion = NEW.expiracion AND tipo_id_id = NEW.destino_id;
    ELSE
        -- Insert a new entry
        INSERT INTO inventario (producto_id_id, expiracion, tipo_id_id, cantidad)
        VALUES (NEW.producto_id_id, NEW.expiracion, NEW.destino_id, NEW.cantidad);
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


-- -- Create the trigger
CREATE TRIGGER tr_handle_transferencia_insert
AFTER INSERT ON transferencia
FOR EACH ROW
EXECUTE FUNCTION handle_transferencia_insert();


-- -- Inserting data for Inventario (Bodegas)
INSERT INTO inventario (id, tipo_id_id, producto_id_id, cantidad, expiracion) VALUES
('1e5383a7-1ed5-46b2-b429-c8efb0664105', 1, '45b0ca36-99aa-4f8c-adee-0fad7fa3e1e1', 5, '2024-03-08'),
('bae1bcd4-281e-4c8b-bd45-cde0dd8a3a05', 1, '45b0ca36-99aa-4f8c-adee-0fad7fa3e1e1', 5, '2024-03-08'),
('631a227d-78c2-4028-bc19-41cb0aec2ee6', 1, '45b0ca36-99aa-4f8c-adee-0fad7fa3e1e1', 5, '2024-03-07'),
('2787bdea-2074-4774-ac01-21487c5c3802', 1, 'e8ee44ab-3d71-4029-9519-b149af10a137', 10, '2024-04-15'),
('b3182043-7f2e-4203-8007-2fe0345bd479', 1, 'abc1bf57-93a4-447a-8f4c-7e0a71d3d9c8', 5, '2024-05-22');

-- -- Inserting data for Inventario (Abastecimiento)
INSERT INTO inventario (id, tipo_id_id, producto_id_id, cantidad, expiracion) VALUES
('769e7fa8-124f-4294-a34a-2b6d58cc23d5', 2, '3173993f-81bb-40d7-b7ad-4b139fbdf90b', 5, '2024-03-08'),
('cbf24c7e-50f8-4781-96a1-2214123fe41c', 2, '3173993f-81bb-40d7-b7ad-4b139fbdf90b', 5, '2024-03-08'),
('f3f1d8e0-adf6-48a8-9ce0-c56660011f7e', 2, '3173993f-81bb-40d7-b7ad-4b139fbdf90b', 5, '2024-03-07'),
('4c30781b-0332-467b-8e31-cce74dfc09ba', 2, '7b70d11a-d49f-4f3f-8558-2927f7af1be4', 10, '2024-04-15'),
('7881c81b-5d87-4cee-a2a5-01445c6db104', 2, '1df04a0e-4575-4528-a4b9-46904576ce9a', 5, '2024-05-22');

-- -- Inserting data for Inventario (Venta)
INSERT INTO inventario (id, tipo_id_id, producto_id_id, cantidad, expiracion) VALUES
('9ea3214b-c673-4bf4-9f40-0d906eae5f53', 3, 'ce735395-cf3d-4a09-bc2e-aec233695052', 5, '2024-03-08'),
('db2edfbe-0d29-49d0-abab-06d8740ec232', 3, 'ce735395-cf3d-4a09-bc2e-aec233695052', 5, '2024-03-08'),
('b41ac0ba-5a89-4882-bf1a-fc78365c1be1', 3, 'ce735395-cf3d-4a09-bc2e-aec233695052', 5, '2024-03-07'),
('8b548eb4-2bbb-4d30-96c8-85cfa48bcb2d', 3, '10d9436c-0cad-4010-88ff-07d8591f3865', 10, '2024-04-15'),
('0a67fedb-e9ff-4394-8ec5-15c8e3723b2d', 3, 'da4a3aa3-645a-4b1c-9b5f-dc121aafec83', 5, '2024-05-22');

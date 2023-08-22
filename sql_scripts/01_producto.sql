-- Add uuid-ossp extension to support auto generated uuids
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Alter Producto Table to seet id to default auto generate uuid (must run this again on every migration)
ALTER TABLE producto
ALTER COLUMN id SET DEFAULT uuid_generate_v4();
-- Alter Dimension Table to seet id to default auto generate uuid (must run this again on every migration)
ALTER TABLE dimension
ALTER COLUMN id SET DEFAULT uuid_generate_v4();
-- Alter Envase Table to seet id to default auto generate uuid (must run this again on every migration)
ALTER TABLE envase
ALTER COLUMN id SET DEFAULT uuid_generate_v4();

-- Inserting data for Dimension
INSERT INTO dimension (id, largo, ancho, alto, unidad) VALUES
('1a005722-7ce0-4623-8625-ab8240976101', 10, 5, 20, 'cm'),
('c2545148-0164-4e3e-a3ab-1ce434573e40', 12, 6, 22, 'cm'),
('840c1f1e-3ef7-4ec6-8e13-2e1fb2cb2200', 15, 7, 25, 'cm'),
('c52f501d-1777-4b67-a67a-d54971b97597', 18, 8, 28, 'cm'),
('34540571-fbbe-4c16-8afd-8ce2bba90e29', 20, 10, 30, 'cm'),
('399fcb65-5def-47a2-8803-686366b3d472', 22, 11, 32, 'cm');

-- Inserting data for Envase
INSERT INTO envase (id, nombre, descripcion, material, volumen, unidad_vol, dimension_id_id) VALUES
('18ecd631-4d55-4de3-8b19-61745bb8c5b9', 'Plastic Bottle', 'Standard plastic bottle.', 'Plastic', 500, 'mL', '1a005722-7ce0-4623-8625-ab8240976101'),
('80f82c84-e9bc-4481-8d89-4e5c4b95997b', 'Glass Bottle', 'Standard glass bottle.', 'Glass', 550, 'mL', 'c2545148-0164-4e3e-a3ab-1ce434573e40'),
('cb23e31b-ac08-45e9-8d02-27d54777029f', 'Plastic Container', 'Standard plastic container.', 'Plastic', 600, 'mL', '840c1f1e-3ef7-4ec6-8e13-2e1fb2cb2200'),
('1d0cec9e-806b-479e-9e4f-698fb9140d4d', 'Glass Jar', 'Standard glass jar.', 'Glass', 650, 'mL', 'c52f501d-1777-4b67-a67a-d54971b97597'),
('ecd991dc-7127-401e-9236-b0eb04ad7777', 'Plastic Tube', 'Standard plastic tube.', 'Plastic', 700, 'mL', '34540571-fbbe-4c16-8afd-8ce2bba90e29'),
('fe119765-c685-4b24-a3e8-e430d93d61f9', 'Glass Vial', 'Standard glass vial.', 'Glass', 750, 'mL', '399fcb65-5def-47a2-8803-686366b3d472');

-- Inserting data for Ingrediente
INSERT INTO ingrediente (id, nombre, descripcion) VALUES
('3aa602c0-6877-41b7-931a-8a1e62df3818', 'ibuprofen', 'Nonsteroidal anti-inflammatory drug.'),
('e2875b64-9717-4404-a8e2-9f6d62bf65d8', 'paracetamol', 'Pain reliever and fever reducer.'),
('f3e781fb-cb38-4063-9c91-5c36028174d4', 'acetaminophen', 'Pain reliever and fever reducer.'),
('5b27dd4e-90b8-4300-9f6a-5d7e25adfeed', 'cetirizine hydrochloride', 'Antihistamine used to treat allergies.'),
('84b712e0-d442-4ac6-ac0a-2950039b2217', 'diphenhydramine hydrochloride', 'Antihistamine used to treat allergies and insomnia.'),
('386a280a-4062-4ef1-8bfb-7d3eaef38218', 'naproxen', 'Nonsteroidal anti-inflammatory drug.'),
('6b091ac8-7f26-4d1c-89fb-4e10467f3eaf', 'dextromethorphan hydrobromide', 'Cough suppressant.'),
('7bcc2826-66eb-4f57-8a43-64d4ca03f866', 'phenylephrine hydrochloride', 'Nasal decongestant.'),
('08febc60-4774-467e-b432-262489ff4a9e', 'doxylamine succinate', 'Antihistamine used to treat allergies and insomnia.');

-- Inserting data for Producto
INSERT INTO producto (id, nombre, descripcion, envase_id_id) VALUES
('45b0ca36-99aa-4f8c-adee-0fad7fa3e1e1', 'Advil', 'Pain reliever and fever reducer.', '18ecd631-4d55-4de3-8b19-61745bb8c5b9'),
('e8ee44ab-3d71-4029-9519-b149af10a137', 'Tylenol', 'Pain reliever and fever reducer.', '80f82c84-e9bc-4481-8d89-4e5c4b95997b'),
('abc1bf57-93a4-447a-8f4c-7e0a71d3d9c8', 'Zyrtec', 'Allergy relief.', '80f82c84-e9bc-4481-8d89-4e5c4b95997b'),
('3173993f-81bb-40d7-b7ad-4b139fbdf90b', 'Benadryl', 'Allergy relief.', 'fe119765-c685-4b24-a3e8-e430d93d61f9'),
('7b70d11a-d49f-4f3f-8558-2927f7af1be4', 'Aleve', 'Pain reliever and fever reducer.', 'cb23e31b-ac08-45e9-8d02-27d54777029f'),
('1df04a0e-4575-4528-a4b9-46904576ce9a', 'DayQuil', 'Cough, cold, and flu relief.', 'cb23e31b-ac08-45e9-8d02-27d54777029f'),
('ce735395-cf3d-4a09-bc2e-aec233695052', 'NyQuil', 'Nighttime cough, cold, and flu relief.', '1d0cec9e-806b-479e-9e4f-698fb9140d4d'),
('10d9436c-0cad-4010-88ff-07d8591f3865', 'ZzzQuil', 'Sleep aid.', '1d0cec9e-806b-479e-9e4f-698fb9140d4d'),
('da4a3aa3-645a-4b1c-9b5f-dc121aafec83', 'Prescription Strength Advil', 'Pain reliever and fever reducer.', 'ecd991dc-7127-401e-9236-b0eb04ad7777'),
('c9397260-ab4c-419f-8070-63ffd1067a08', 'Prescription Strength Tylenol', 'Pain reliever and fever reducer.', 'ecd991dc-7127-401e-9236-b0eb04ad7777'),
('fa31cd4c-feb7-4995-8b2f-901cb68074d2', 'Prescription Strength Zyrtec', 'Allergy relief.', 'fe119765-c685-4b24-a3e8-e430d93d61f9'),
('210b1789-69af-4000-95d9-1143dd9385ac', 'Prescription Strength Benadryl', 'Antihistamine.', '18ecd631-4d55-4de3-8b19-61745bb8c5b9'),
('547023a0-c056-4acc-95c7-a786d422ec20', 'Prescription Strength Aleve', 'Antihistamine.', '18ecd631-4d55-4de3-8b19-61745bb8c5b9');

-- Associating Ingredientes with Productos
-- many-to-many relation table named producto_ingrediente
INSERT INTO producto_ingrediente (producto_id, ingrediente_id) VALUES
('45b0ca36-99aa-4f8c-adee-0fad7fa3e1e1', '3aa602c0-6877-41b7-931a-8a1e62df3818'),
('45b0ca36-99aa-4f8c-adee-0fad7fa3e1e1', 'e2875b64-9717-4404-a8e2-9f6d62bf65d8'),
('e8ee44ab-3d71-4029-9519-b149af10a137', 'f3e781fb-cb38-4063-9c91-5c36028174d4'),
('abc1bf57-93a4-447a-8f4c-7e0a71d3d9c8', '5b27dd4e-90b8-4300-9f6a-5d7e25adfeed'),
('3173993f-81bb-40d7-b7ad-4b139fbdf90b', '84b712e0-d442-4ac6-ac0a-2950039b2217'),
('7b70d11a-d49f-4f3f-8558-2927f7af1be4', '386a280a-4062-4ef1-8bfb-7d3eaef38218'),
('1df04a0e-4575-4528-a4b9-46904576ce9a', '6b091ac8-7f26-4d1c-89fb-4e10467f3eaf'),
('1df04a0e-4575-4528-a4b9-46904576ce9a', 'f3e781fb-cb38-4063-9c91-5c36028174d4'),
('1df04a0e-4575-4528-a4b9-46904576ce9a', '7bcc2826-66eb-4f57-8a43-64d4ca03f866'),
('ce735395-cf3d-4a09-bc2e-aec233695052', '6b091ac8-7f26-4d1c-89fb-4e10467f3eaf'),
('ce735395-cf3d-4a09-bc2e-aec233695052', 'f3e781fb-cb38-4063-9c91-5c36028174d4'),
('ce735395-cf3d-4a09-bc2e-aec233695052', '7bcc2826-66eb-4f57-8a43-64d4ca03f866'),
('10d9436c-0cad-4010-88ff-07d8591f3865', '6b091ac8-7f26-4d1c-89fb-4e10467f3eaf'),
('10d9436c-0cad-4010-88ff-07d8591f3865', 'f3e781fb-cb38-4063-9c91-5c36028174d4'),
('10d9436c-0cad-4010-88ff-07d8591f3865', '7bcc2826-66eb-4f57-8a43-64d4ca03f866'),
('da4a3aa3-645a-4b1c-9b5f-dc121aafec83', '3aa602c0-6877-41b7-931a-8a1e62df3818'),
('c9397260-ab4c-419f-8070-63ffd1067a08', 'f3e781fb-cb38-4063-9c91-5c36028174d4'),
('fa31cd4c-feb7-4995-8b2f-901cb68074d2', '5b27dd4e-90b8-4300-9f6a-5d7e25adfeed'),
('210b1789-69af-4000-95d9-1143dd9385ac', '84b712e0-d442-4ac6-ac0a-2950039b2217'),
('547023a0-c056-4acc-95c7-a786d422ec20', '386a280a-4062-4ef1-8bfb-7d3eaef38218');

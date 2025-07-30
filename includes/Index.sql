CREATE TABLE bitacora (
    bita_id INT AUTO_INCREMENT PRIMARY KEY,
    bita_fecha DATE NOT NULL,
    bita_malware INT NOT NULL,
    bita_pishing INT NOT NULL,
    bita_coman_cont INT NOT NULL,
    bita_cryptomineria INT NOT NULL,
    bita_ddos INT NOT NULL,
    bita_conex_bloq INT NOT NULL,
    bita_total INT NOT NULL,
    bita_situacion INT NOT NULL DEFAULT 1
);
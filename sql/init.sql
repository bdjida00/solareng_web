-- =============================================
-- Solar Engineering - Projekti modul
-- Pokrenuti jednom u phpMyAdmin ili MySQL konzoli
-- =============================================

CREATE TABLE IF NOT EXISTS projekti (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    naslov          VARCHAR(255)    NOT NULL,
    lokacija        VARCHAR(255)    NOT NULL,
    opis            TEXT,
    snaga_kw        DECIMAL(6,2),
    tip_panela      VARCHAR(255),
    tip_invertera   VARCHAR(255),
    napomena        TEXT,
    datum_ugradnje  DATE,
    datum_objave    DATETIME        DEFAULT NOW(),
    aktivan         TINYINT(1)      DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS projekt_slike (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    projekt_id  INT         NOT NULL,
    putanja     VARCHAR(500) NOT NULL,
    redoslijed  INT         DEFAULT 0,
    FOREIGN KEY (projekt_id) REFERENCES projekti(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

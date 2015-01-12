ALTER TABLE `ovx_zadavatel` ADD `koeficient` DECIMAL( 4, 2 ) NOT NULL DEFAULT '1';

CREATE TRIGGER dodavatel_update AFTER UPDATE ON ovx_dodavatel
FOR EACH ROW BEGIN
  INSERT INTO ovx_udalosti SET id=new.id, datum=NOW(), tabulka="dodavatel", akce="update";
END;
CREATE TRIGGER dodavatel_insert AFTER INSERT ON ovx_dodavatel
FOR EACH ROW BEGIN
  INSERT INTO ovx_udalosti SET id=new.id, datum=NOW(), tabulka="dodavatel", akce="insert";
END;
CREATE TRIGGER dodavatel_delete BEFORE DELETE ON ovx_dodavatel
FOR EACH ROW BEGIN
  INSERT INTO ovx_udalosti SET id=old.id, datum=NOW(), tabulka="dodavatel", akce="delete";
END;

CREATE TRIGGER zadavatel_update AFTER UPDATE ON ovx_zadavatel
FOR EACH ROW BEGIN
  INSERT INTO ovx_udalosti SET id=new.id, datum=NOW(), tabulka="zadavatel", akce="update";
END;
CREATE TRIGGER zadavatel_insert AFTER INSERT ON ovx_zadavatel
FOR EACH ROW BEGIN
  INSERT INTO ovx_udalosti SET id=new.id, datum=NOW(), tabulka="zadavatel", akce="insert";
END;
CREATE TRIGGER zadavatel_delete BEFORE DELETE ON ovx_zadavatel
FOR EACH ROW BEGIN
  INSERT INTO ovx_udalosti SET id=old.id, datum=NOW(), tabulka="zadavatel", akce="delete";
END;

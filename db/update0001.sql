CREATE TABLE htwlabel ( id, label, PRIMARY KEY(id, label) );
CREATE TABLE htwlabels ( name, color, icon, initial, labelEN, labelFR, labelES, PRIMARY KEY(name) );
CREATE INDEX idx_id ON htwlabel(id);
CREATE INDEX idx_name ON htwlabels(name);
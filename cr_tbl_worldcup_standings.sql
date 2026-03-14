CREATE TABLE worldcup_standings (
    id INT PRIMARY KEY,
    Country VARCHAR(255),
    Played INT,
    Wins INT,
    Loss INT,
    Draws INT,
    Points  INT
);

--use data import wizard to import data into db.
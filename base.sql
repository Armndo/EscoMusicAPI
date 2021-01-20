drop database escomusic;
create database escomusic;
use escomusic;

create table `user`(
    id int not null auto_increment,
    email varchar(320) not null,
    password varchar(20) not null,
    name varchar(60) not null,
    primary key(id),
    unique(email)
);

insert into user (email, password, name) values ("armndo.g@gmail.com", "123", "armando"), ("test@mail.com", "123", "test");

create table genre(
    id int not null auto_increment,
    genre varchar(40) not null,
    description text,
    primary key(id),
    unique(genre)
);

create table instrument(
    id int not null auto_increment,
    instrument varchar(40) not null,
    description text,
    primary key(id),
    unique(instrument)
);

create table artist(
    id int not null auto_increment,
    name varchar(60) not null,
    birthday date,
    gender varchar(30),
    country varchar(60),
    years_active varchar(30),
    primary key(id)
);

create table band(
    id int not null auto_increment,
    band varchar(60) not null,
    created date,
    country varchar(60),
    years_active varchar(30),
    primary key(id)
);

create table media(
    id int not null auto_increment,
    media varchar(60),
    primary key(id)
);

create table album(
    id int not null auto_increment,
    album varchar(60) not null,
    released date,
    recorded date,
    length varchar(10),
    primary key(id)
);

create table song(
    id int not null auto_increment,
    song varchar(60) not null,
    lyrics text,
    released date,
    recorded varchar(4),
    length varchar(10),
    album_id int,
    primary key(id),
    foreign key(album_id) references album(id) on delete cascade on update cascade
);

create table songwritter(
    artist_id int not null,
    song_id int not null,
    primary key(artist_id, song_id),
    foreign key(artist_id) references artist(id) on delete cascade on update cascade,
    foreign key(song_id) references song(id) on delete cascade on update cascade
);

create table artist_album(
    artist_id int not null,
    album_id int not null,
    primary key(artist_id, album_id),
    foreign key(artist_id) references artist(id) on delete cascade on update cascade,
    foreign key(album_id) references album(id) on delete cascade on update cascade
);

create table band_album(
    band_id int not null,
    album_id int not null,
    primary key(band_id, album_id),
    foreign key(band_id) references band(id) on delete cascade on update cascade,
    foreign key(album_id) references album(id) on delete cascade on update cascade
);

create table album_genre(
    album_id int not null,
    genre_id int not null,
    primary key(album_id, genre_id),
    foreign key(album_id) references album(id) on delete cascade on update cascade,
    foreign key(genre_id) references genre(id) on delete cascade on update cascade
);

create table artist_instrument(
    artist_id int not null,
    instrument_id int not null,
    primary key(artist_id, instrument_id),
    foreign key(artist_id) references artist(id) on delete cascade on update cascade,
    foreign key(instrument_id) references instrument(id) on delete cascade on update cascade
);

create table artist_genre(
    artist_id int not null,
    genre_id int not null,
    primary key(artist_id, genre_id),
    foreign key(artist_id) references artist(id) on delete cascade on update cascade,
    foreign key(genre_id) references genre(id) on delete cascade on update cascade
);

create table band_genre(
    band_id int not null,
    genre_id int not null,
    primary key(band_id, genre_id),
    foreign key(band_id) references band(id) on delete cascade on update cascade,
    foreign key(genre_id) references genre(id) on delete cascade on update cascade
);

create table song_genre(
    song_id int not null,
    genre_id int not null,
    primary key(song_id, genre_id),
    foreign key(song_id) references song(id) on delete cascade on update cascade,
    foreign key(genre_id) references genre(id) on delete cascade on update cascade
);

create table artist_media(
    artist_id int not null,
    media_id int not null,
    primary key(artist_id, media_id),
    foreign key(artist_id) references artist(id) on delete cascade on update cascade,
    foreign key(media_id) references media(id) on delete cascade on update cascade
);

create table band_media(
    band_id int not null,
    media_id int not null,
    primary key(band_id, media_id),
    foreign key(band_id) references band(id) on delete cascade on update cascade,
    foreign key(media_id) references media(id) on delete cascade on update cascade
);

create table member(
    artist_id int not null,
    band_id int not null,
    primary key(band_id, artist_id),
    foreign key(band_id) references band(id) on delete cascade on update cascade,
    foreign key(artist_id) references artist(id) on delete cascade on update cascade
);
# Breadcrumbs

This application displays trails based on GPS coordinates provided by a third-part service.


## Usage

### Basic authentification

Only IDs listed in the `$authorizedIds` array in `config/config.php` can add points to the database.
An ID is a MD5 hash of the phone’s serial number and a string of your choice.
To generate a MD5 hash, [replace `SERIAL_NUMBER` and `PASSWORD` in `md5 SERIAL_NUMBER PASSWORD`](https://duckduckgo.com/?q=md5+SERIAL_NUMBER+PASSWORD).

### Adding points

Once this is done, you can add points by making a request to your application with the following GET parameters:

- lat: latitude;
- lon: longitude;
- alt: altitude;
- time: time for that location;
- spd: speed at that time;
- dir: direction for that location;
- ser: serial number of your device;
- pwd: password of your choice;

### Visualization of the trail

Once some points are available, you can simply load your application and you will see the latest points on the map, centered on the latest point recorded.


## Developer environment

1. Install npm and vagrant on your machine.
1. Clone this repository anywhere on your computer.
1. Run `vagrant up`.
1. Run `npm install`.


## Useful tools

[GPSLogger for Android](https://github.com/mendhak/gpslogger/) is a handy tool to publish GPS coordinates to a whole bunch of servcices.

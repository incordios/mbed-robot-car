# Web-Controlled Ultrasonic Robot With Live-Streaming Camera

Georgia Tech ECE 4180 Final Project

Team Members: Prakhar Mittal, Meghna Jain, Minseung Jung, Nicolas Rios

This project was built on an Mbed and Raspberry Pi 4.

## Description

We have created a robot car that can be controlled using a web page deployed using an Apache 2 web server. The user can press the forward, backward, left, right, or stop buttons on the web page to send motion commands to the car. The sensors on the robot provide 360 degree coverage. First, the robot uses a Raspberry Pi 4 to livestream video feedback from a Pi Camera mounted at the front. Moreover, an Ultrasonic sensor mounted at the rear detects and streams the distance from the closest obstacles behind. If the robot goes too close to an obstacle (<30 cm), a siren sound is played on the speakers and the robot stops automatically.

## List of Components

* Sparkfun RedBot with Shadow Chassis
* Mbed board
* Raspberry Pi (we used a Pi 4 but any Pi should work)
* Pi Camera
* TB6612FNG Dual H-Bridge
* HC-SR04 Ultrasonic Sensor
* TPA2005D1 Class D Audio Amp
* Speaker
* Two 5VDC 2A AC adapters

## Set Up Pi Camera and Motion Server

As the robot moves around, the Raspberry Pi has a Pi Camera connected to stream what the robot sees. To set up the Pi Camera, we used a tutorial referenced in the ECE 4180 Lab 4. https://www.raspberrypi.org/learning/addons-guide/picamera/

The website allowed us to ensure the connected Pi Camera was configured correctly and activated for use within the Raspberry Pi Zero W. Once knowing it was active, we tested the camera using the commands:

raspistill -o cam.jpg for taking pictures
raspivid -o vid.h264 for taking videos

Once knowing that the Pi Camera works, we utilized another tutorial referenced in the ECE 4180 Lab 4 on creating a video streaming server. http://www.instructables.com/id/How-to-Make-Raspberry-Pi-Webcam-Server-and-Stream-/

We set up a server called Motion and we configure the server to run when one types in the Raspberry Pi’s IP address followed by the Port 8081. The format of the URL is:

<code><IP_ADDRESS>:<PORTNUMBER></code>

A live streaming video should show up on a person’s web browser. In addition, we configured the motion server to run as a daemon within the Raspberry Pi whenever it boots up, allowing for motion server to run as a background process instead of waiting for user to start the service. All of this was done as described within the Motion Server tutorial.

## Set up Apache2 server

For setting up the main web page for streaming the Pi Camera and LIDAR values, we set up an Apache2 server using the tutorial referenced in the ECE 4180 Lab 4. https://www.raspberrypi.org/documentation/remote-access/web-server/apache.md

Once setting up the server, we placed the distance.php file within the apache2 server directory located at /var/www/html.

 Within the same directory, we made another php file called index.php that will create a web page divided into two sections. The top section of the page calls the motion server located at the Raspberry Pi’s IP Address at port 8081. The bottom section of the page calls the the separate distance.php file. The distance.php is called and refreshed using a Javascript function call that calls the php file every 3 seconds. These two sections are separated using the <div> command where it is used to divide the php document into separate sections. As the top section displays the motion server running the video stream, the bottom section refreshes every 3 seconds, where the distance.php file reads the LIDAR distance values and displays it on the web page.
 
 References: 
https://www.w3schools.com/tags/tag_div.asp
https://www.w3schools.com/tags/tag_img.asp
https://www.w3schools.com/jsref/met_win_setinterval.asp
http://api.jquery.com/load/

## MBED Componenet Pinouts
 
* uSD Breakout
 
 |  uSD breakout |      mbed    |
 |---------------|--------------|
 |      CS       |      p29     |
 |      DI       | p5(SPI mosi) |
 |     VCC       |     VOUT     |
 |     SCK       | p7(SPI sclk) |
 |     GND       |     GND      |
 |      DO       | p6(SPI miso) |
 |      CD       |     nc       |
 
 * Audio Amp and Speaker
 
 |  mbded  |  Class D Audio Amp  | Speaker | Battery Pack |
 |---------|---------------------|---------|--------------|
 |   GND   |     PWR, IN-        |         |              |
 |         |       PWR+          |         |      5V      |
 |   p18   |        IN+          |         |              |
 |         |       OUT+          |    +    |              |
 |         |       OUT-          |    -    |              |
 
 * Ultrasonic Module
 
 |  mbed    |   HC-SR04   |
 |----------|-------------|
 |  Vu(5V)  |     Vcc     |
 |   Gnd    |     Gnd     |
 |    p6    |     trig    |
 |    p7    |     echo    |
 
 * H-Bridge and Motors
 
 |  H-bridge  |  mbed  |  Right Motor  |  Left Motor  |  battery pack  |
 |------------|--------|---------------|--------------|----------------|
 |     VM     |        |               |              |        +       |
 |    VCC     |  VOUT  |               |              |                |
 |    GND     |   GND  |               |              |        -       |
 |    STBY    |  VOUT  |               |              |                |
 |    PWMA    |   p22  |               |              |                |
 |    AIN1    |   p13  |               |              |                |
 |    AIN2    |   p12  |               |              |                |
 |    AO1     |        |       +       |              |                |
 |    AO2     |        |       -       |              |                |
 |    PWMA    |   p21  |               |              |                |
 |    AIN1    |   p8   |               |              |                |
 |    AIN2    |   p11  |               |              |                |
 |    AO1     |        |               |       +      |                |
 |    AO2     |        |               |       -      |                |
 
On the Raspberry Pi, run 
 <code>sudo service motion restart && sudo motion</code>

Navigate to `localhost`.

# mbed-robot-car
Georgia Tech ECE 4180 Final Project

This project was build on an Mbed and Raspberry Pi 4. 

== Description of Project ==

The purpose of this project to create a Bluetooth controlled robot with a variety of features. A user is able to control its movement through the use of an Apache Webserver. As the robot is moving, it senses how far away it is to objects using a Ultrasonic sensor attached to its front. As the robot detects objects, it will produce a varying loud sound that changes in volume as the robot moves closer or farther away. This robot also utilizes a Raspberry Pi 4 with an attached Raspberry Pi Camera to livestream video feedback on what it sees, as well as the Ultrasonic sensor values, to a web page.

== List of Components Needed ===
* Mbed - Adafruit Bluefruit LE UART Friend module
* TPA2005D1 Class D Audio Amp
* uSD card breakout
* Speaker
* TB6612FNG Dual H-Bridge
* 5VDC 2A AC adapter, the barrel jack adapter
* Sparkfun RedBot with Shadow Chassis
* Raspberry Pi
* Pi Camera

== Reference for the Volume Code in the above program ===

In the above program, some code was borrowed from a previous 4180 lab group. The code can be found here:

https://os.mbed.com/users/sarthakjaiswal/notebook/mbed-music-player/

This code was used to modify the "waveplayer.h" file, which allowed for the volume of the sound (int level variable) to change based on LIDAR values (uint32_t distance variable).



== Set Up Pi Camera and Motion Server ==

As the robot moves around, the Raspberry Pi has a Pi Camera connected to stream what the robot sees. To set up the Pi Camera, we used a tutorial referenced in the ECE 4180 Lab 4. https://www.raspberrypi.org/learning/addons-guide/picamera/

The website allowed us to ensure the connected Pi Camera was configured correctly and activated for use within the Raspberry Pi Zero W. Once knowing it was active, we tested the camera using the commands:

raspistill -o cam.jpg for taking pictures
raspivid -o vid.h264 for taking videos

Once knowing that the Pi Camera works, we utilized another tutorial referenced in the ECE 4180 Lab 4 on creating a video streaming server. http://www.instructables.com/id/How-to-Make-Raspberry-Pi-Webcam-Server-and-Stream-/

We set up a server called Motion and we configure the server to run when one types in the Raspberry Pi’s IP address followed by the Port 8081. The format of the URL is:

<code><IP_ADDRESS>:<PORTNUMBER></code>

A live streaming video should show up on a person’s web browser. In addition, we configured the motion server to run as a daemon within the Raspberry Pi whenever it boots up, allowing for motion server to run as a background process instead of waiting for user to start the service. All of this was done as described within the Motion Server tutorial.

== Set up Apache2 server ==
For setting up the main web page for streaming the Pi Camera and LIDAR values, we set up an Apache2 server using the tutorial referenced in the ECE 4180 Lab 4. https://www.raspberrypi.org/documentation/remote-access/web-server/apache.md

Once setting up the server, we placed the distance.php file within the apache2 server directory located at /var/www/html.

 ```
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
   "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<?php header('Access-Control-Allow-Origin: *'); ?>
<script src="scripts/jquery.min.js"></script>
   <script type="text/javascript">
 function autoRefresh_div()
 {
      $("#distance").load("distance.php");// a function which will load data from other file after x seconds
  }
 
  setInterval('autoRefresh_div()', 3000); // refresh div after 3 secs
            </script>

</head>
<body>
<table>
<tr>
<td height="600px">
<div id="result" >
<img style="-webkit-user-select: none;" src="http://<?php echo $_SERVER['SERVER_ADDR']; ?>:8081/">
</div>
</td>
</tr>
<tr>
<td>
<div id="distance">
<br><b>Distance read from LIDAR</b><br>
</div>
</td></tr></table>
</body>
</html>
 ```
 
 Within the same directory, we made another php file called index.php that will create a web page divided into two sections. The top section of the page calls the motion server located at the Raspberry Pi’s IP Address at port 8081. The bottom section of the page calls the the separate distance.php file. The distance.php is called and refreshed using a Javascript function call that calls the php file every 3 seconds. These two sections are separated using the <div> command where it is used to divide the php document into separate sections. As the top section displays the motion server running the video stream, the bottom section refreshes every 3 seconds, where the distance.php file reads the LIDAR distance values and displays it on the web page.
 
 References: 
https://www.w3schools.com/tags/tag_div.asp
https://www.w3schools.com/tags/tag_img.asp
https://www.w3schools.com/jsref/met_win_setinterval.asp
http://api.jquery.com/load/



On the Raspberry Pi, run 
`sudo service motion restart && sudo motion`

Navigate to `localhost`.

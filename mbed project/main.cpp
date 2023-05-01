#include "mbed.h"
#include "rtos.h"
#include "ultrasonic.h"
#include "Motor.h"
#include "Speaker.h"
#include <stdio.h>


// Motors
float straightSpeed = 0.6;
float turnSpeed = 0.5;
Motor right(p21, p11, p8); // pwm, fwd, rev
Motor left(p22, p13, p12); // pwm, fwd, rev


// Control
RawSerial pi(USBTX, USBRX);
DigitalOut led1(LED1);
DigitalOut led2(LED2);
DigitalOut led3(LED3);
DigitalOut led4(LED4);

char control = '0';
char prevControl = '0';
void control_receive() {
    while (pi.readable()) {
        if (pi.getc() == 'c' && pi.getc() == 't' && pi.getc() == 'r' && pi.getc() == 'l') {
            prevControl = control;
            control = pi.getc();
        }
        led1 = control == '1';
        led2 = control == '2';
        led3 = control == '3';
        led4 = control == '4';
    }
}


// Ultrasonic
bool eStop = false;

void dist(int distance) {    
    eStop = distance < 300;
    pi.printf("Rear Distance: %ld mm\n", distance);
}

ultrasonic sensor(p6, p7, 1, 1, &dist); // trig, echo

Thread sensorThread;
void sensorFunction() {
    while (true) {
        sensor.checkDistance();
        Thread::wait(100);
    }
}


// Speaker
Speaker speaker(p26);

Thread speakerThread;
void speakerFunction() {
    while (true) {
        if (eStop) {
            speaker.PlayNote(698, 0.3, 1);
        } else {
            speaker.PlayNote(698, 0.3, 0);
        }
        Thread::wait(100);
    }
}


int main() {
    pi.baud(9600);
    pi.attach(&control_receive, Serial::RxIrq);
    sensor.startUpdates();
    sensorThread.start(sensorFunction);
    speakerThread.start(speakerFunction);
    while (true) {
        if (eStop && control == '2') {
            right.speed(0);
            left.speed(0);
        } else {
            if (control == '1') {
                right.speed(straightSpeed);
                left.speed(straightSpeed);
            } else if (control == '2') {
                right.speed(-straightSpeed);
                left.speed(-straightSpeed);
            } else if (control == '3') {
                right.speed(-turnSpeed);
                left.speed(turnSpeed);
            } else if (control == '4') {
                right.speed(turnSpeed);
                left.speed(-turnSpeed);
            } else {
                right.speed(0);
                left.speed(0);
            } 
        }
        Thread::wait(100);
    }
}

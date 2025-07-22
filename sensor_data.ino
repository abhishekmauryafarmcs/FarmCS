#include "DHT.h"
#include <Wire.h>
#include <BH1750.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <ESP32Servo.h>

#define DHTPIN 4
#define DHTTYPE DHT11
#define SDA_PIN 21
#define SCL_PIN 22
#define LED_PIN 12  // LED connected to pin 12
#define SERVO_PIN 13  // First servo connected to pin 13
#define SERVO2_PIN 14  // Second servo connected to pin 14

// Ultrasonic sensor pins
const int trigPin = 5;
const int echoPin = 18;

// Define sound speed in cm/uS
#define SOUND_SPEED 0.034
#define CM_TO_INCH 0.393701

// Soil moisture sensor variables
int _moisture, sensor_analog;
const int sensor_pin = 34;  // Soil moisture sensor O/P pin

// WiFi credentials
const char* ssid = "ABHISHEK FarmCS";
const char* password = "123456789";

// Your local server address - Update with your computer's IP address
const char* serverName = "http://192.168.137.1:8080/FarmCS/handlers/receive_sensor_data.php";
const char* ledControlEndpoint = "http://192.168.137.1:8080/FarmCS/handlers/led_control.php";
const char* servoControlEndpoint = "http://192.168.137.1:8080/FarmCS/handlers/servo_control.php";
const char* servo2ControlEndpoint = "http://192.168.137.1:8080/FarmCS/handlers/servo2_control.php";

// Unique device identifier
const char* DEVICE_ID = "FARM_SENSOR_001";

DHT dht(DHTPIN, DHTTYPE);
BH1750 lightMeter;
Servo myservo;  // Create first servo object
Servo myservo2; // Create second servo object
bool lightSensorFound = false;

// Ultrasonic sensor variables
long duration;
float distanceCm;

// LED control variables
unsigned long lastLedCheck = 0;
const unsigned long LED_CHECK_INTERVAL = 1000; // Check LED state every 1 second

// Servo control variables
unsigned long lastServoCheck = 0;
const unsigned long SERVO_CHECK_INTERVAL = 1000; // Check servo angle every 1 second
const unsigned long SERVO_UPDATE_DELAY = 50; // Minimum time between updates in milliseconds
int currentServoAngle = 0;

// Servo 2 control variables
unsigned long lastServo2Check = 0;
const unsigned long SERVO2_CHECK_INTERVAL = 100;
int currentServo2Angle = 0;
int targetServo2Angle = 0;

// WiFi connection retry parameters
const int maxWiFiAttempts = 10;
const int wifiRetryDelay = 500;
bool isWiFiConnected = false;

void setupWiFi() {
    WiFi.begin(ssid, password);
    Serial.println("\nConnecting to WiFi...");
    
    int attempts = 0;
    while (WiFi.status() != WL_CONNECTED && attempts < maxWiFiAttempts) {
        delay(wifiRetryDelay);
        Serial.print(".");
        attempts++;
    }
    
    if (WiFi.status() == WL_CONNECTED) {
        Serial.println("\nConnected to WiFi!");
        Serial.print("IP Address: ");
        Serial.println(WiFi.localIP());
        isWiFiConnected = true;
    } else {
        Serial.println("\nFailed to connect to WiFi!");
        isWiFiConnected = false;
    }
}

void checkWiFiConnection() {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("WiFi connection lost. Reconnecting...");
        WiFi.disconnect();
        delay(1000);
        setupWiFi();
    }
}

void setup() {
    Serial.begin(115200);
    Serial.println("\nInitializing...");
    
    // Initialize WiFi first
    setupWiFi();
    
    // Initialize pins
    pinMode(trigPin, OUTPUT);
    pinMode(echoPin, INPUT);
    pinMode(sensor_pin, INPUT);
    pinMode(LED_PIN, OUTPUT);  // Initialize LED pin
    digitalWrite(LED_PIN, LOW);  // LED starts off
    
    // Initialize Servos with specific timers
    ESP32PWM::allocateTimer(0);
    ESP32PWM::allocateTimer(1);
    ESP32PWM::allocateTimer(2);
    ESP32PWM::allocateTimer(3);
    
    myservo.setPeriodHertz(50);    // Standard 50 hz servo
    myservo2.setPeriodHertz(50);   // Standard 50 hz servo
    
    // Attach servos with min/max pulse widths
    myservo.attach(SERVO_PIN, 500, 2400);
    myservo2.attach(SERVO2_PIN, 500, 2400);
    
    // Set initial positions
    myservo.write(0);
    myservo2.write(0);
    currentServo2Angle = 0;
    
    Serial.println("Servos initialized");
    
    // Initialize I2C
    Wire.begin(SDA_PIN, SCL_PIN);
    
    // Initialize DHT11
    dht.begin();
    Serial.println("DHT11 Initialized");
    
    // Initialize BH1750 with error checking
    if (lightMeter.begin(BH1750::CONTINUOUS_HIGH_RES_MODE)) {
        Serial.println("BH1750 Light Sensor Initialized");
        lightSensorFound = true;
    } else {
        Serial.println("Error initializing BH1750 Light Sensor! Check your wiring...");
        lightSensorFound = false;
    }
}

void checkLedState() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(ledControlEndpoint);
    
    int httpResponseCode = http.GET();
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("LED Response: " + response);
      
      // Parse JSON response
      DynamicJsonDocument doc(1024);
      DeserializationError error = deserializeJson(doc, response);
      
      if (!error) {
        bool state = doc["state"];
        digitalWrite(LED_PIN, state ? HIGH : LOW);
        Serial.println(state ? "LED ON" : "LED OFF");
      }
    }
    
    http.end();
  }
}

void checkServoAngle() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(servoControlEndpoint);
    
    int httpResponseCode = http.GET();
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Servo Response: " + response);
      
      // Parse JSON response
      DynamicJsonDocument doc(1024);
      DeserializationError error = deserializeJson(doc, response);
      
      if (!error) {
        int newAngle = doc["angle"];
        if (newAngle != currentServoAngle) {
          currentServoAngle = newAngle;
          myservo.write(currentServoAngle);
          Serial.println("Servo angle updated to: " + String(currentServoAngle));
        }
      }
    }
    
    http.end();
  }
}

void checkServo2Angle() {
    if (!isWiFiConnected) {
        Serial.println("WiFi not connected. Cannot check servo2 angle.");
        return;
    }
    
    HTTPClient http;
    String endpoint = String(servo2ControlEndpoint);
    
    Serial.print("Connecting to endpoint: ");
    Serial.println(endpoint);
    
    http.begin(endpoint);
    http.setTimeout(5000); // Set 5 second timeout
    
    int httpResponseCode = http.GET();
    Serial.print("Servo2 HTTP Response code: ");
    Serial.println(httpResponseCode);
    
    if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.print("Servo2 Response: ");
        Serial.println(response);
        
        DynamicJsonDocument doc(1024);
        DeserializationError error = deserializeJson(doc, response);
        
        if (!error) {
            int newAngle = doc["angle"];
            if (newAngle != currentServo2Angle) {
                targetServo2Angle = newAngle;
                myservo2.write(targetServo2Angle);
                currentServo2Angle = targetServo2Angle;
                Serial.print("Servo2 moved to angle: ");
                Serial.println(currentServo2Angle);
            }
        } else {
            Serial.print("JSON Parse Error: ");
            Serial.println(error.c_str());
        }
    } else {
        Serial.print("Error accessing endpoint. HTTP Error code: ");
        Serial.println(httpResponseCode);
    }
    
    http.end();
}

void loop() {
    // Check WiFi connection first
    checkWiFiConnection();
    
    if (!isWiFiConnected) {
        delay(5000); // Wait 5 seconds before retrying
        return;
    }
    
    unsigned long currentMillis = millis();
    
    // Check LED state periodically
    if (currentMillis - lastLedCheck >= LED_CHECK_INTERVAL) {
        checkLedState();
        lastLedCheck = currentMillis;
    }
    
    // Check servos periodically
    if (currentMillis - lastServoCheck >= SERVO_CHECK_INTERVAL) {
        checkServoAngle();
        lastServoCheck = currentMillis;
    }
    
    if (currentMillis - lastServo2Check >= SERVO2_CHECK_INTERVAL) {
        checkServo2Angle();
        lastServo2Check = currentMillis;
    }
    
    // Read DHT11 sensor
    float h = dht.readHumidity();
    float t = dht.readTemperature();
    
    // Read light sensor with error checking
    float lux = 0;
    if (lightSensorFound) {
        lux = lightMeter.readLightLevel();
        if (lux < 0) {
            Serial.println("Error reading light sensor!");
            lux = 0;
        }
    }
    
    // Read ultrasonic sensor
    digitalWrite(trigPin, LOW);
    delayMicroseconds(2);
    digitalWrite(trigPin, HIGH);
    delayMicroseconds(10);
    digitalWrite(trigPin, LOW);
    
    duration = pulseIn(echoPin, HIGH);
    distanceCm = duration * SOUND_SPEED/2;
    
    // Read soil moisture sensor
    sensor_analog = analogRead(sensor_pin);
    _moisture = ( 100 - ( (sensor_analog/4095.00) * 100 ) );
    
    // Print all sensor readings to Serial Monitor
    Serial.println("\n--- Sensor Readings ---");
    
    // DHT11 readings
    if (isnan(h) || isnan(t)) {
        Serial.println("Failed to read from DHT11 sensor!");
    } else {
        Serial.print("Temperature: ");
        Serial.print(t);
        Serial.print("°C, Humidity: ");
        Serial.print(h);
        Serial.println("%");
    }
    
    // Light sensor readings
    Serial.print("Light: ");
    Serial.print(lux);
    Serial.println(" lx");
    
    // Distance readings
    Serial.print("Distance: ");
    Serial.print(distanceCm);
    Serial.println(" cm");
    
    // Moisture readings
    Serial.print("Soil Moisture: ");
    Serial.print(_moisture);
    Serial.println("%");

    // Check WiFi connection status
    if(WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        
        http.begin(serverName);
        http.addHeader("Content-Type", "application/json");
        
        // Prepare JSON payload for sensor data
        String jsonPayload = "{\"soil_moisture\":" + String(_moisture) + 
                           ",\"temperature\":" + String(t) + 
                           ",\"humidity\":" + String(h) + 
                           ",\"light_intensity\":" + String(lux) + 
                           ",\"device_id\":\"" + String(DEVICE_ID) + "\"}";
        
        // Print request data for debugging
        Serial.print("Sending JSON data: ");
        Serial.println(jsonPayload);
        
        int httpResponseCode = http.POST(jsonPayload);
        
        if (httpResponseCode > 0) {
            String response = http.getString();
            Serial.print("HTTP Response code: ");
            Serial.println(httpResponseCode);
            Serial.println("Response: " + response);
        }
        else {
            Serial.print("Error sending data. HTTP Error code: ");
            Serial.println(httpResponseCode);
        }
        
        http.end();
    }
    else {
        Serial.println("WiFi Disconnected");
        WiFi.begin(ssid, password);
    }
    
    delay(2000);  // Wait for 2 seconds before next reading
}
#include <SPI.h>
#include <Ethernet.h>
#include <SoftwareSerial.h>

byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
IPAddress ip(172, 19, 207, 200);
IPAddress server(172, 19, 207, 184);

IPAddress gateway(172, 19, 0, 1);
IPAddress subnet(255, 255, 0, 0);

EthernetClient client;
SoftwareSerial rfidReader(2, 3);

void setup() {
  Serial.begin(9600);
  rfidReader.begin(9600);
  
  Serial.println("Initialisation du reseau sur le switch...");
  Ethernet.begin(mac, ip, server, gateway, subnet);
  delay(1500); 
  
  Serial.println("Lecteur Grove pret a scanner !");
}

void loop() {
  if (rfidReader.available() > 0) {
    String uidString = "";
    delay(50); 
    
    while (rfidReader.available() > 0) {
      char c = rfidReader.read();
      if (isAlphaNumeric(c)) {
        uidString += c;
      }
    }
    
    if (uidString.length() > 0) {
      Serial.print("Badge detecte : ");
      Serial.println(uidString);

      envoyerDonnees(uidString);
      delay(2000); 
    }
  }
}

void envoyerDonnees(String uid) {
  if (client.connect(server, 80)) {
    Serial.println("Connecte a XAMPP. Envoi en cours...");
    
    client.print("GET /gestion_badges/api_scan.php?badge=");
    client.print(uid);
    client.println(" HTTP/1.1");
    client.print("Host: ");
    client.println(server);
    client.println("Connection: close");
    client.println();
    
    client.stop();
    Serial.println("Requete envoyee avec succes !");
  } else {
    Serial.println("ERREUR : Impossible de se connecter a XAMPP (Verifiez le pare-feu Windows).");
  }
}

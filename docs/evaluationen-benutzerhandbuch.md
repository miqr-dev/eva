# Benutzerhandbuch: Evaluationen erstellen und TANs ausgeben

Version: 1.0  
Stand: 13.08.2026  
Zielgruppe: interne Mitarbeitende, die Evaluationen vorbereiten, starten und TANs an Teilnehmende ausgeben.

## 1. Grundidee der Plattform

Die Plattform hat zwei getrennte Bereiche:

- Interner Bereich: Mitarbeitende melden sich an und verwalten Standorte, Kurse, Lehrende, Module, Fragebögen, Evaluationen und TANs.
- Öffentlicher TAN-Bereich: Teilnehmende melden sich nicht an. Sie öffnen die öffentliche Evaluationsseite, geben ihre TAN ein und beantworten den Fragebogen anonym.

Teilnehmende haben keine Benutzerkonten. Eine TAN steuert nur den Zugang zur Evaluation und wird nach der Abgabe verbraucht.

## 2. Rollen und Berechtigungen

Mitarbeitende benötigen passende Berechtigungen. Für das Erstellen und Verwalten von Evaluationen wird aktuell die Berechtigung `campaigns.manage` verwendet.

Typische Rollen:

- Super-Administration: kann alle Verwaltungsbereiche bedienen.
- Administration: kann die Verwaltungsbereiche bedienen.
- Fragebogen-Redaktion: erstellt Module und Fragebögen.
- Berichtszugriff: sieht später Ergebnisse und Berichte.

Wenn eine Seite nicht sichtbar ist oder mit "403" blockiert wird, fehlt dem Benutzer wahrscheinlich die passende Berechtigung.

## 3. Wichtige Navigation

Nach dem Login befindet sich die Hauptnavigation oben beziehungsweise links im Verwaltungsbereich.

Wichtige Menüpunkte für Evaluationen:

- Standorte: Bundesländer und Standorte wie Thüringen, Erfurt, Sachsen, Leipzig verwalten.
- Lehrende: Lehrpersonen verwalten.
- Kurse: Kurse anlegen und Lehrende zuordnen.
- Module: wiederverwendbare Fragenblöcke anlegen.
- Frageneditor: Modulversionen, Abschnitte und Fragen bearbeiten und veröffentlichen.
- Fragebögen: Fragebogenvorlagen anlegen.
- Fragebogen-Builder: veröffentlichte Module zu einer Fragebogenversion zusammenstellen.
- Evaluationen: konkrete Evaluationen anlegen und TANs erzeugen.

## 4. Gesamtworkflow

Eine Evaluation funktioniert nur sauber, wenn die Vorarbeit in der richtigen Reihenfolge erledigt wurde:

1. Standort prüfen oder anlegen.
2. Lehrende anlegen.
3. Kurs anlegen und Lehrende zuordnen.
4. Module anlegen.
5. Im Frageneditor eine Modulversion erstellen, Abschnitte und Fragen hinzufügen und die Modulversion veröffentlichen.
6. Eine Fragebogenvorlage anlegen.
7. Im Fragebogen-Builder eine Fragebogenversion erstellen, veröffentlichte Module hinzufügen, Wiederholung festlegen und die Version veröffentlichen.
8. Unter Evaluationen eine konkrete Evaluation erstellen.
9. Für die Evaluation TANs erzeugen.
10. TANs an Teilnehmende weitergeben.
11. Teilnehmende füllen die Evaluation anonym aus.
12. Nach Ende der Evaluation werden Antworten und später Berichte ausgewertet.

## 5. Standort, Kurs und Lehrende vorbereiten

### Standort

Pfad: Verwaltung -> Standorte

Ein Standort gehört normalerweise zu einem Bundesland. Beispiel:

- Thüringen
  - Erfurt
  - Suhl
- Sachsen
  - Chemnitz
  - Döbeln
  - Dresden
  - Leipzig
  - Riesa
- Berlin
  - Prenzlauer Promenade
  - Trachenbergring

Viele Datensätze werden später nach Standort gefiltert. Deshalb sollte ein Kurs immer dem richtigen Standort zugeordnet werden.

### Lehrende

Pfad: Verwaltung -> Lehrende

Lehrende können mit oder ohne Benutzerkonto angelegt werden. Für Evaluationen reicht ein Lehrenden-Datensatz mit Name, E-Mail-Adresse und Standort. Ein Login ist nicht zwingend notwendig.

### Kurs

Pfad: Verwaltung -> Kurse

Beim Kurs werden diese Daten gepflegt:

- Kursname, zum Beispiel "Deutsch B2".
- Kurscode, zum Beispiel "DE-B2-2026-09".
- Standort.
- Beginn und Ende, falls bekannt.
- Zugeordnete Lehrende.

Wichtig: Wenn später ein Dozentenmodul pro Lehrperson erscheinen soll, müssen die Lehrenden dem Kurs zugeordnet sein.

## 6. Module und Fragen vorbereiten

### Modul anlegen

Pfad: Verwaltung -> Module

Ein Modul ist ein wiederverwendbarer Block von Fragen. Beispiele:

- Organisation
- Dozent / Unterricht
- Räume / Technik
- Kursinhalt
- Abschlusskommentar

Das Modul selbst ist nur der Container. Die eigentlichen Fragen liegen in Modulversionen.

### Modulversion bearbeiten

Pfad: Verwaltung -> Frageneditor

Im Frageneditor wird eine Entwurfsversion erstellt. In dieser Version werden Abschnitte und Fragen angelegt.

Unterstützte Fragetypen:

- Skala: Bewertung mit Zahlen, zum Beispiel 1 bis 5.
- Einfachauswahl: genau eine Antwortoption.
- Freitext: freie Texteingabe.
- Ja / Nein: einfache Zustimmung oder Ablehnung.

Beispiel für eine Skalenfrage:

- Frage: "Der Dozent erklärt verständlich."
- Typ: Skala
- Minimum: 1
- Maximum: 5
- Minimum-Label: "Stimme überhaupt nicht zu"
- Maximum-Label: "Stimme voll zu"

### Modulversion veröffentlichen

Eine Modulversion kann erst veröffentlicht werden, wenn sie mindestens eine Frage enthält.

Nach der Veröffentlichung ist die Version gesperrt. Das ist Absicht: Alte Evaluationen müssen immer exakt mit der damaligen Frageversion verbunden bleiben. Für Änderungen wird eine neue Entwurfsversion erstellt.

## 7. Fragebogen zusammenstellen

### Fragebogenvorlage anlegen

Pfad: Verwaltung -> Fragebögen

Eine Fragebogenvorlage ist der übergeordnete Name, zum Beispiel "Kursbewertung". Die eigentliche nutzbare Fassung ist eine Fragebogenversion.

### Fragebogenversion im Builder erstellen

Pfad: Verwaltung -> Fragebogen-Builder

Im Fragebogen-Builder wird eine Entwurfsversion erstellt. Danach werden veröffentlichte Modulversionen hinzugefügt.

Für jedes Modul wird festgelegt:

- Reihenfolge im Fragebogen.
- Wiederholung:
  - Einmal: Das Modul erscheint einmal.
  - Pro Zielperson: Das Modul erscheint pro Zielperson, zum Beispiel pro Lehrperson.

Beispiel:

- Organisation: Einmal.
- Dozent / Unterricht: Pro Zielperson.
- Abschlusskommentar: Einmal.

Wenn ein Kurs zwei Lehrende hat, erscheint das Dozentenmodul später zweimal:

- Dozent / Unterricht - Frau Müller
- Dozent / Unterricht - Herr Schmidt

### Fragebogenversion veröffentlichen

Die Schaltfläche "Version veröffentlichen" ist nur nutzbar, wenn die Fragebogenversion mindestens ein Modul enthält.

Nach der Veröffentlichung ist die Fragebogenversion gesperrt und kann für Evaluationen ausgewählt werden.

## 8. Evaluation erstellen

Pfad: Verwaltung -> Evaluationen -> Evaluation anlegen

Beim Anlegen einer Evaluation werden diese Felder gepflegt:

- Titel: eindeutiger Name der Evaluation, zum Beispiel "Deutsch B2 Kursbewertung September 2026".
- Beschreibung: optionaler Hinweistext.
- Standort: Standort der Evaluation.
- Kurs: optional, aber für Kursevaluationen empfohlen.
- Veröffentlichte Fragebogenversion: Pflichtfeld.
- Beginn: Startzeitpunkt.
- Ende: Endzeitpunkt.
- Status: Entwurf, Geplant, Aktiv, Geschlossen oder Archiviert.
- Mindestanzahl Antworten für Ergebnisse: schützt die Anonymität.

Statusbedeutung:

- Entwurf: Die Evaluation ist vorbereitet, aber noch nicht für Teilnehmende nutzbar.
- Geplant: Die Evaluation ist bereit, aber noch nicht aktiv.
- Aktiv: Teilnehmende können mit gültiger TAN antworten, wenn der Zeitraum passt.
- Geschlossen: Es werden keine Antworten mehr angenommen.
- Archiviert: Die Evaluation ist abgeschlossen und aus dem normalen Arbeitsfluss entfernt.

Wichtig: Teilnehmende können nur antworten, wenn die Evaluation aktiv ist und der aktuelle Zeitpunkt innerhalb von Beginn und Ende liegt.

## 9. TANs erzeugen

Pfad: Verwaltung -> Evaluationen -> TANs

In der Evaluationen-Liste gibt es pro Evaluation die Aktion "TANs". Diese öffnet die TAN-Seite der Evaluation.

Auf der TAN-Seite sehen Sie:

- Titel, Standort, Kurs, Fragebogen und Zeitraum.
- Teilnehmer-Link.
- TAN-Statistik:
  - TANs gesamt
  - Unbenutzt
  - Gestartet
  - Verwendet
  - Inaktiv

So erzeugen Sie TANs:

1. Anzahl der benötigten TANs eintragen.
2. "TANs erzeugen" klicken.
3. Die angezeigten TANs sofort kopieren oder als TXT-Datei herunterladen.
4. Die TANs an die Teilnehmenden weitergeben.

Wichtig: Die TANs werden nur einmal im Klartext angezeigt. In der Datenbank wird nur ein Hash gespeichert. Nach dem Neuladen der Seite können die gleichen TANs nicht erneut im Klartext angezeigt werden.

Für geschlossene oder archivierte Evaluationen können keine neuen TANs erzeugt werden.

## 10. TANs an Teilnehmende senden

Aktueller Stand der App:

- TANs können erzeugt, kopiert und als TXT-Datei heruntergeladen werden.
- Die TANs werden aktuell manuell an Teilnehmende gesendet.
- Automatische E-Mail-Jobs sind in der Datenstruktur vorgesehen, aber noch nicht als vollständiger normaler Versandworkflow umgesetzt.

Empfohlene manuelle Nachricht:

Betreff: Ihre TAN zur Kursevaluation

Text:

Guten Tag,

bitte nehmen Sie an der anonymen Kursevaluation teil.

Link: /evaluation  
Ihre TAN: [TAN einsetzen]

Die Teilnahme ist anonym. Bitte verwenden Sie die TAN nur einmal.

Vielen Dank.

## 11. Teilnehmenden-Workflow

Teilnehmende öffnen die öffentliche Evaluationsseite:

`/evaluation`

Ablauf:

1. TAN eingeben.
2. System prüft TAN, Zeitraum und Status der Evaluation.
3. Fragebogen wird dynamisch aus der Datenbank geladen.
4. Teilnehmende beantworten die Fragen.
5. Teilnehmende senden die Evaluation ab.
6. Die TAN wird als verwendet markiert.
7. Eine Dankeseite wird angezeigt.

Die Antworten werden anonym gespeichert. Es wird kein Teilnehmername und kein Teilnehmerkonto gespeichert.

## 12. Was im Hintergrund passiert

Bei TAN-Eingabe prüft das System:

- Existiert die TAN?
- Ist die TAN aktiv?
- Wurde die TAN bereits verwendet?
- Ist die TAN abgelaufen?
- Ist die Evaluation aktiv?
- Liegt der aktuelle Zeitpunkt zwischen Beginn und Ende?

Beim Absenden:

- Es wird ein Antwortdatensatz erstellt.
- Alle Antworten werden einzeln gespeichert.
- Die TAN wird als verwendet markiert.
- Alles passiert in einer Datenbank-Transaktion, damit keine halben Antworten entstehen.

## 13. Ergebnisse und Anonymität

Die Mindestanzahl Antworten schützt die Anonymität.

Beispiel:

- Mindestanzahl: 5
- Abgegebene Antworten: 3
- Ergebnisansicht bleibt gesperrt.

Erst wenn die Mindestanzahl erreicht ist, sollen Ergebnisse und Berichte sichtbar werden.

## 14. Häufige Probleme

### Ich kann keine Fragebogenversion in der Evaluation auswählen

Wahrscheinlich ist die Fragebogenversion noch nicht veröffentlicht. Öffnen Sie den Fragebogen-Builder und veröffentlichen Sie die Version.

### Die Fragebogenversion lässt sich nicht veröffentlichen

Die Version enthält wahrscheinlich noch kein Modul. Fügen Sie mindestens eine veröffentlichte Modulversion hinzu.

### Im Fragebogen-Builder gibt es keine Module zur Auswahl

Es gibt noch keine veröffentlichte Modulversion. Öffnen Sie den Frageneditor, erstellen Sie Fragen und veröffentlichen Sie die Modulversion.

### Teilnehmende sehen "Diese Evaluation ist derzeit nicht aktiv"

Prüfen Sie:

- Status der Evaluation muss "Aktiv" sein.
- Beginn darf nicht in der Zukunft liegen.
- Ende darf nicht überschritten sein.
- TAN muss aktiv und unbenutzt sein.

### Eine TAN ist ungültig

Mögliche Ursachen:

- TAN wurde falsch eingegeben.
- TAN wurde nie erzeugt.
- Leerzeichen oder Tippfehler.
- TAN gehört zu einer anderen Umgebung oder Datenbank.

### Das Dozentenmodul erscheint nicht pro Lehrperson

Prüfen Sie:

- Der Kurs hat Lehrende zugeordnet.
- Das Modul ist als Lehrpersonenmodul gedacht.
- Im Fragebogen-Builder ist "Pro Zielperson" ausgewählt.
- Die Evaluation hat passende Ziele für Lehrende.

Hinweis: Wenn die Zielerzeugung für Kampagnen noch nicht automatisch in der Oberfläche sichtbar ist, muss dieser Teil im nächsten Entwicklungsschritt ergänzt oder geprüft werden.

## 15. Checkliste vor dem Start einer Evaluation

- Standort ist korrekt.
- Kurs ist korrekt.
- Lehrende sind dem Kurs zugeordnet.
- Alle benötigten Module sind veröffentlicht.
- Die Fragebogenversion ist veröffentlicht.
- Evaluation hat den richtigen Standort, Kurs und Fragebogen.
- Beginn und Ende sind korrekt.
- Status ist auf "Aktiv", wenn Teilnehmende starten sollen.
- Genügend TANs wurden erzeugt.
- TANs wurden kopiert oder heruntergeladen.
- Teilnehmende haben Link und TAN erhalten.

## 16. Kurzfassung für Evaluation Manager

1. Kurs und Lehrende prüfen.
2. Fragebogen veröffentlichen.
3. Evaluation anlegen.
4. Status und Zeitraum prüfen.
5. In der Evaluationen-Liste auf "TANs" klicken.
6. Anzahl eintragen und TANs erzeugen.
7. TANs kopieren oder als TXT herunterladen.
8. Link `/evaluation` und TANs an Teilnehmende senden.
9. Antworten überwachen.
10. Nach Ende Evaluation schließen und Ergebnisse prüfen.


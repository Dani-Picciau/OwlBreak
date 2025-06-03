# OwlBreak
Alcune precisazioni per il funzionamento del progetto:  
* Per quanto riguarda la parte della Professoressa Brodo, tutto il necessario, comprese password e utenti, si trova all’interno di owlbreak/SQL/database_olwbreak.sql;  
* Per quanto riguarda la parte del Professor Ghiani, in modo tale che il sito web non crei problematiche e rispetti il corretto funzionamento dei percorsi è necessario che l’intera cartella owlbreak venga messa all’interno di xampp/htdocs/… .
Tutti i file riguardanti la parte web si troveranno dentro owlbreak/website/….

# Precisazioni
* Come concordato con il Professor Ghiani, non sono stati sviluppati i profili per tutti i tipi di utente.  
  Nell'applicazione web sono infatti presenti solo i profili per i clienti (Studente, Personale-Ata, Personale-Docente, Personale-Segreteria) e per gli operatori addetti alle consegne, ognuno di essi con differenti privilegi all'interno dell'applicazione.
  
# Profili utente per applicazione web e password:
**Clienti con ordini effettuati**:  
Studente: d.picciau@studenti.boscogrigio.it e m.manai@studenti.boscogrigio.it
Personale-Segreteria: l.ghiani@boscogrigio.it e l.brodo@boscogrigio.it  

È comunque possibile effettuare degli ordini con qualsiasi altro cliente peresnte nel database.
Se si vuole vedere un cliente senza ordini si può scegliere un utente qualunque dalla tabella clienti come:   
m.rossi@studenti.boscogrigio.it per gli studenti e l.riva@boscogrigio.it per il personale docente.

**Operatore:**   
Addetto-consegne: m.cau@owlbreak.it e e.serra@owlbreak.it
  
# Ogni account presente nel database ha password: Pluto_paperino12


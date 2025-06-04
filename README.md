# OwlBreak
Alcune precisazioni per il funzionamento del progetto:  
* Per quanto riguarda la parte della Professoressa Brodo, tutto il necessario, comprese password e utenti, si trova all’interno di owlbreak/SQL/database_olwbreak.sql;  
* Per quanto riguarda la parte del Professor Ghiani, in modo tale che il sito web non crei problematiche e rispetti il corretto funzionamento dei percorsi è necessario che l’intera cartella owlbreak venga messa all’interno di xampp/htdocs/… .
Tutti i file riguardanti la parte web si troveranno dentro owlbreak/website/….

# Precisazioni
* Come concordato con il Professor Ghiani, non sono stati sviluppati i profili per tutti i tipi di utente.  
  Nell'applicazione web sono infatti presenti solo i profili per i clienti (Studente, Personale-Ata, Personale-Docente, Personale-Segreteria) e per gli operatori addetti alle consegne, ognuno di essi con differenti privilegi all'interno dell'applicazione.

* L'utente admin è utilizzato solo nel file java in quanto non era stato previsto un utilizzo di un admin per l'applicazione web. 
  Quest'ultima è stata infatti sviluppata per scopo accademico e per questo l'admin del database siamo noi stessi con l'utente root.

* Visto il contesto per il quale si volevano realizzare il database e il sito web, sono stati implementati dei vincoli sull'ordine da parte del cliente.  
  Quest'ultimo era infatti previsto solo dalle 8:00 alle 10:00 e dal lunedì a sabato. Per semplicità e comodità di test, sono stati modificati.  
  Si potrà notare che il trigger per il controllo sul giorno della settimana è commentato, così come il pezzo equivalente nella procedura effettua_ordine.
  Il trigger sul controllo dell'orario è invece stato modificato per permettere ordini dalle 8:00 alle 22:00, stessa cosa per il controllo equivalente che è stato implementato nella procedura effettua_ordine. 
  Qualora si vogliano testare queste funzionalità per determinati giorni e orari è possibile decommentare i pezzi di codice indicati sopra o modificare gli orari a prorpio piacimento. 
  
# Profili utente per applicazione web:
**Clienti con ordini effettuati**:  
Studente: d.picciau@studenti.boscogrigio.it e m.manai@studenti.boscogrigio.it
Personale-Segreteria: l.ghiani@boscogrigio.it e l.brodo@boscogrigio.it  

È comunque possibile effettuare degli ordini con qualsiasi altro cliente peresnte nel database.
Se si vuole vedere un cliente senza ordini si può scegliere un utente qualunque dalla tabella clienti come:   
m.rossi@studenti.boscogrigio.it per gli studenti e l.riva@boscogrigio.it per il personale docente.

**Operatore:**   
Addetto-consegne: m.cau@owlbreak.it e e.serra@owlbreak.it

# Prolfili per app java:
**Operatore:**   
Titolare: c.ricci@owlbreak.it
Oppure qualsiasi altri addetto alle vendite

**Fornitore**
info.consegne@fornosanmarco.it
O qualsiasi altro fornitore
  
# Ogni account presente nel database ha password: Pluto_paperino12


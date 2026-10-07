# API

Ett litet REST-liknande API i PHP för att hämta, skapa, uppdatera och radera sidor. Sidornas innehåll lagras som JSON i MariaDB och rubriker finns på flera språk (svenska, engelska och tyska).

Alla anrop görs med URL-parametrar mot `api.php` och svaret kommer alltid som JSON.

## Innehåll

- [Kom igång](#kom-igång)
- [Snabböversikt](#snabböversikt)
- [Hämta sidor](#hämta-sidor)
- [Skapa en sida](#skapa-en-sida--actioncreate)
- [Uppdatera en sida](#uppdatera-en-sida--actionupdate)
- [Radera en sida](#radera-en-sida--actiondelete)
- [Parametrar](#parametrar)
- [Format för content](#format-för-content)
- [Felkoder](#felkoder)
- [Testa med curl](#testa-med-curl)
- [Projektstruktur](#projektstruktur)
- [Databas](#databas)

## Kom igång

Projektet körs med [DDEV](https://ddev.com/) (PHP 8.4, nginx och MariaDB 11.8).

```bash
ddev start
```

API:t finns sedan på:

```
https://api.ddev.site/api.php
```

## Snabböversikt

| Vad du vill göra | Anrop |
| --- | --- |
| Hämta alla sidor | `api.php` |
| Hämta en sida via ID | `api.php?id=1` |
| Hämta en sida via tagg | `api.php?tag=start` |
| Hämta på ett annat språk | `api.php?lang=en` |
| Skapa en sida | `api.php?action=create&titel=...&content=...` |
| Uppdatera en sida | `api.php?action=update&id=1&titel=...` |
| Radera en sida | `api.php?action=delete&id=1` |

## Hämta sidor

Om du inte skickar med någon `action` hämtar API:t sidor. Varje sida har de här fälten:

| Fält | Beskrivning |
| --- | --- |
| `id` | Sidans ID |
| `titel` | Sidans titel |
| `tag` | Namnet på sidans tagg |
| `rubrik` | Rubriken på valt språk (`lang`) |
| `hero_bild` | Sökväg till sidans hero-bild |

### Alla sidor

```
GET api.php
```

Svaret är en lista:

```json
[
    {
        "id": 1,
        "titel": "Startsida",
        "tag": "start",
        "rubrik": "Välkommen",
        "hero_bild": "start.jpg"
    },
    {
        "id": 2,
        "titel": "Om oss",
        "tag": "om",
        "rubrik": "Om oss",
        "hero_bild": "om.jpg"
    }
]
```

### En sida via ID

```
GET api.php?id=1
```

Svaret är ett enskilt objekt:

```json
{
    "id": 1,
    "titel": "Startsida",
    "tag": "start",
    "rubrik": "Välkommen",
    "hero_bild": "start.jpg"
}
```

### En sida via tagg

```
GET api.php?tag=start
```

Du får den **första** sidan som har taggen, som ett enskilt objekt.

### Välja språk

Lägg till `lang` på vilket hämtningsanrop som helst för att få rubriken på ett annat språk:

```
GET api.php?id=1&lang=en
```

```json
{
    "id": 1,
    "titel": "Startsida",
    "tag": "start",
    "rubrik": "Welcome",
    "hero_bild": "start.jpg"
}
```

Om rubriken saknas på det språket blir `rubrik` `null`.

> Exemplen ovan visar formatet. Vilka sidor och taggar som finns beror på vad som ligger i din databas.

## Skapa en sida – `action=create`

```
api.php?action=create&titel=Om oss&content={"rubrik":{"sv":"Om oss","en":"About us"},"hero_bild":"om.jpg"}
```

| Parameter | Krävs | Beskrivning |
| --- | --- | --- |
| `titel` | Ja | Sidans titel |
| `content` | Ja | Sidans innehåll som giltig JSON ([se format](#format-för-content)) |
| `lang` | Nej | Sidans språk, `sv` om inget anges |

Svar (status `201 Created`):

```json
{
    "success": true,
    "id": 5
}
```

> Nya sidor får alltid tagg-ID `1`.

## Uppdatera en sida – `action=update`

Skicka med `id` och det du vill ändra. Det du inte skickar med lämnas orört.

Bara ny titel:

```
api.php?action=update&id=5&titel=Ny titel
```

Bara nytt innehåll:

```
api.php?action=update&id=5&content={"rubrik":{"sv":"Ny rubrik","en":"New heading"},"hero_bild":"ny.jpg"}
```

Båda på en gång:

```
api.php?action=update&id=5&titel=Ny titel&content={"rubrik":{"sv":"Ny rubrik"}}
```

| Parameter | Krävs | Beskrivning |
| --- | --- | --- |
| `id` | Ja | ID för sidan som ska uppdateras |
| `titel` | Nej | Ny titel |
| `content` | Nej | Nytt innehåll som giltig JSON. **Ersätter** hela det gamla innehållet. |

Svar:

```json
{
    "success": true,
    "message": "Sida 5 har uppdaterats"
}
```

## Radera en sida – `action=delete`

```
api.php?action=delete&id=5
```

| Parameter | Krävs | Beskrivning |
| --- | --- | --- |
| `id` | Ja | ID för sidan som ska raderas |

Både sidan och dess innehåll tas bort.

Svar:

```json
{
    "success": true,
    "message": "Sida 5 har raderats"
}
```

Finns ingen sida med det ID:t får du `404`.

## Parametrar

| Parameter | Tillåtna värden | Standard | Används av |
| --- | --- | --- | --- |
| `action` | `create`, `update`, `delete` | ingen (hämtar sidor) | alla |
| `id` | positivt heltal | – | hämta, update, delete |
| `tag` | taggnamn | – | hämta |
| `lang` | `sv`, `en`, `de` | `sv` | hämta, create |
| `titel` | text | – | create, update |
| `content` | giltig JSON | – | create, update |

Om både `id` och `tag` skickas med används `id`.

## Format för content

`content` är ett JSON-objekt. API:t läser just de här fälten:

```json
{
    "rubrik": {
        "sv": "Välkommen",
        "en": "Welcome",
        "de": "Willkommen"
    },
    "hero_bild": "start.jpg"
}
```

| Fält | Typ | Beskrivning |
| --- | --- | --- |
| `rubrik.sv` / `rubrik.en` / `rubrik.de` | text | Rubriken på respektive språk |
| `hero_bild` | text | Sökväg eller URL till hero-bilden |

Du kan lägga till fler fält. De sparas, men visas inte i svaren.

## Felkoder

Vid fel svarar API:t med en HTTP-statuskod och ett JSON-objekt:

```json
{
    "error": "Sidan hittades inte"
}
```

| Status | `error` | Orsak |
| --- | --- | --- |
| `400` | Språket stöds inte | `lang` är inte `sv`, `en` eller `de` |
| `400` | ID måste vara ett nummer | `id` är inte ett heltal |
| `400` | Titel och content krävs | `create` utan `titel` eller `content` |
| `400` | Content måste vara giltig JSON | `content` går inte att tolka som JSON |
| `400` | ID krävs för att uppdatera | `update` utan `id` |
| `400` | ID krävs för att radera | `delete` utan `id` |
| `400` | Okänd action | `action` har ett värde som inte finns |
| `404` | Sidan hittades inte | Ingen sida med det ID:t eller den taggen, eller inga sidor alls |

## Testa med curl

Enkla anrop:

```bash
curl https://api.ddev.site/api.php
curl "https://api.ddev.site/api.php?id=1&lang=en"
curl "https://api.ddev.site/api.php?tag=start"
```

För anrop med JSON eller mellanslag är det enklast att låta curl URL-koda åt dig med `-G` och `--data-urlencode`:

```bash
# Skapa
curl -G https://api.ddev.site/api.php \
  --data-urlencode "action=create" \
  --data-urlencode "titel=Om oss" \
  --data-urlencode 'content={"rubrik":{"sv":"Om oss","en":"About us"},"hero_bild":"om.jpg"}'

# Uppdatera
curl -G https://api.ddev.site/api.php \
  --data-urlencode "action=update" \
  --data-urlencode "id=5" \
  --data-urlencode "titel=Ny titel"

# Radera
curl "https://api.ddev.site/api.php?action=delete&id=5"
```

> Lägg till `-k` om curl klagar på certifikatet, eller kör `mkcert -install` så att DDEV:s certifikat blir betrott.

I webbläsaren kan du klistra in anropen direkt i adressfältet. Webbläsaren URL-kodar dem åt dig.

## Projektstruktur

```
.
├── api.php          # Tar emot anropen, validerar parametrar och svarar med JSON
├── funktioner.php   # createPage, updatePage och deletePage
├── db.php           # Databasanslutning (mysqli)
├── index.html       # Dokumentationssida med testruta
└── .ddev/           # DDEV-konfiguration
```

## Databas

Databasen heter `min_api` och använder tre tabeller:

| Tabell | Kolumner som används | Beskrivning |
| --- | --- | --- |
| `page` | `id`, `titel`, `tag_id`, `sprak` | Själva sidorna |
| `tag` | `id`, `namn` | Taggar som sidor kan ha |
| `page_content` | `page_id`, `content_json` | Sidans innehåll som JSON |

Öppna databasen med:

```bash
ddev mysql
```

## Start the stack with docker compose

```bash
$ ./run.sh
```

## Stop the stack with docker compose

```bash
$ ./stop.sh
```

### Elastic search head

```text
http://localhost:9100/
```

### Elastic search

```text
http://localhost:9200/
```

### Generate data

Go to app container and run 

```shell
php index.php
```

Script will generate file `data.json`

### Load data

> PUT /_bulk

data.json

### Autocomplete

> PUT /autocomplete

```json
{
  "mappings": {
    "properties": {
      "word": {
        "type": "search_as_you_type"
      }
    }
  }
}
```

### Reindex

> POST /_reindex?pretty

```json
{
  "source": {
    "index": "words"
  },
  "dest": {
    "index": "autocomplete"
  }
}
```

### Mapping

> GET /autocomplete/_mapping?pretty

```json
{
  "autocomplete" : {
    "mappings" : {
      "properties" : {
        "word" : {
          "type" : "search_as_you_type",
          "doc_values" : false,
          "max_shingle_size" : 3
        }
      }
    }
  }
}
```

### Search query

> GET /autocomplete/_search

```json
{
  "query": {
    "fuzzy": {
      "word": {
        "value": "someword",
        "fuzziness": "AUTO",
        "max_expansions": 50,
        "prefix_length": 0,
        "transpositions": true
      }
    }
  }
}
```
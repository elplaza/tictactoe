# Tic Tac Toe Game

Webservice that exposes two APIs to play to Tic Tac Toe Game.

## How To

### Install
- install [docker](https://docs.docker.com/install/linux/docker-ce/ubuntu/)
- install [docker-compose](https://docs.docker.com/compose/install/)
- install [git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
- clone this repository with `git clone git@github.com:elplaza/tictactoe.git <dir>`
- move to project `<dir>` with `mv <dir>`

### Run
Run tic tac toe webservice:
```bash
docker compose up -d
```

Check all running containers:
```bash
docker compose ps
```

You should see `db` and `php` services running.

### Use
Exposed API:
- Create a new game session (start game):
	- Request POST /public/api/game-sessions
	- Response returns a new game session (id, players, board, etc...)
- Update the specific game session (make a move):
	- Request PUT /public/api/game-sessions/{id}
	- Response returns the game session updated (updated board, player won, etc...)

Call exposed APIs with an http client (cUrl, Postman, Frontend app, etc...) like this:
```bash
curl -X POST http://localhost:8073/public/api/game-sessions
```

```bash
curl -X PUT -d '{"player_id":"<Player ID>","row":<Row>,"column":<Column>}' http://localhost:8073/public/api/game-sessions/<GameSession ID>
```
where:
	- `Player ID` is the ID of the player that makes the move (players IDS are returned from create game session API) 
	- `Column` is the column number (from 1 to 3) 
	- `Row` is the row number (from 1 to 3) 
	- `GameSession ID` is the ID of the current GameSession (game session ID is returned from create game session API)

### Development tools
#### Psalm
Run static code analysis:
```bash
docker compose run php psalm
```

#### PHP_CodeSniffer
Detect coding standard violations:
```bash
docker compose run php cs
```

Fix coding standard violations:
```bash
docker compose run php cbf
```

#### Tests
Run automatic tests:
```bash
docker compose up -d db_test
```
```bash
docker compose run php test
```

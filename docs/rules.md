# Rules for the Code Challenge 2018

![space-invaders.png](images/space-invaders.png "Space Invaders")

The idea is quite simple:
You have to **create a REST API** to **move a player** across a random generated board.
In this board there will be also a number of **enemies** and the other players.
You wil be able to move or fire at any direction (up, down, left or right), but you will have limited shots.

You will know the size of the board, the position of your character and some other data, but you won't see the entire board.
The game engine will call your API, sending the current conditions, and your API will have to response with your next move:
`up`, `down`, `left`, `right`, `fire-up`, `fire-down`, `fire-left` or `fire-right`.

<hr>

## The game

The game board is a matrix of `N x M` cells containing a randomly generated maze.
The width and the height are configurable and can vary from one game to another.
Width: `10 ≤ N ≤ 50`, height: `10 ≤ M ≤ 25`.
Each cell of the game can contain only one item at the same time: a wall, a player or an enemy.

![starship-screen01.png](images/starship-screen01.png "Game Board")

<hr>

![starship-player01-right.png](images/starship-player01-right.png "Player's Starship")

The player's character is moved by the [player's REST API](api.md).
The game engine can manage up to 9 different players concurrently in the same board.

![starship-invader01-regular.png](images/starship-invader01-regular.png "Space Invader")

The **enemies** will kill you if they catch you.
They move randomly across the board.
The number of enemies in the game and the spawn frequency can be configured and it changes from game to game.

![starship-invader02-neutral.png](images/starship-invader02-neutral.png "Neutral Space Invader")

When a new enemy is born, he is **neutral** for some movements.
A neutral enemy cannot kill a player, but players can kill neutral enemies by touching them.
After a few movements a neutral enemy becomes a regular enemy.

![starship-explosion.png](images/starship-explosion.png "Explosion")

When a player is killed, it remains **died** for some movements.
A killed player will respawn in the same place where he was killed.

![starship-shot-right.png](images/starship-shot-right.png "Shot")

This is a **shot** fired by a player.
If a shot hunts a player or an enemy, it kills him.
Shots have a limited range.

<hr>

The shots are instantaneous, but limited to the [**visible area**](api.md#visible-area).
If two players shot each other, both are killed.
After shooting, a player must reload and he cannot shot again until a few movements.

If two or more players try to occupy the same cell at the same time, they are all killed.
If a player occupies the same cell as an enemy, one of them dies.
If the enemy is neutral, he dies.
Otherwise, the player is the one who dies.

The game engine processes all the movements from the players and then the shots.
So a player can dodge a shot.
When all the movements and the shots are processed the game engine moves the enemies.
So the enemies cannot dodge a shot.

The length of the game is defined by the number of movements which is a configurable parameter of the game (50 to 5000).
In every movement, a call is made to each of the players' APIs and all responses are processed.
The players' APIs have a timeout of 3 seconds.
If an API does not respond before the timeout, its movement is considered null.
Since all calls are made in parallel, each movement takes a maximum of 3 seconds.
So a game of 50 movements will take 2.5 seconds maximum.

<hr>

## Rules

### Basics

* The **The Code Challenge 2020** is a programming competition.

* It's opened to **all Veepee employees** particularly to vpTech ones.

* The required programming level to participate is high.

### Restrictions

* No human interaction is allowed within the players' APIs.
That is, the APIs must decide their next move without human intervention.

### Join

* To join the challenge you'll need to **upload your API to your own Internet server**.

* Your API has to be **accessible** from the game server through an URL or IP address.

* No support will be given to create the API or upload it to a server.

### Competition format

* The Competition format will depend on the **number of participants**.

* The idea is to do some **qualifying rounds** to discard non-optimized APIs and then a **big final**.

> #### Example
>
> 50 participants:
>
> * 1st round: 50 players, 6 groups (4 of 8 players + 2 of 9 players) → 18 players qualified for the next round.
> * 2nd round: 18 players, 2 groups of 9 players → 6 players qualified for the final.
> * Final: 6 players, 1 group of 9 players, → 3 top players (1 winner).

* The **number of matches** per group and the number of **movements per match** will change from round to round (as well as the width and height of the board and the number of enemies).

* The duration of the challenge will depend on the **number of participants**, the **matches per group** per each round and the **movements per match** per each round.

> #### Example
>
> 50 participants, 2 rounds and a big final:
>
> * 1st round: 6 groups, 2 matches per group, 50 movements per match (~2.5 minutes) → 12 matches, ~30 minutes.
> * 2nd round: 2 groups, 3 matches per group, 100 movements per match (~5 minutes) → 6 matches, ~30 minutes.
> * Final: 1 group, 4 matches, 150 movements (~7.5 minutes) → 4 matches, ~30 minutes.
>
> Total 1.5 hours.

<hr>

See:

* [API Documentation](api.md)
* [README.md](../README.md)

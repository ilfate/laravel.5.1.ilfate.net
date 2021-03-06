<?php
/**
 * ilfate.net
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2014
 */

namespace Ilfate\Tcg;

class GameBuilder {

    public static function buildGameForBattle($battle)
    {
        $users = $battle->getPlayerWithDecks();
        $players = [];
        $playerNum = 1;

        $game      = new Game();
        $game->log = new GameLog($game);
        $game->gameType = Game::GAME_TYPE_BATTLE;
        $game->setUpGameObject();
        $game->sessionType = Game::IMPORT_TYPE_NORMAL;

        foreach ($users as $user) {
            $currentTeam = ($playerNum > (count($users) / 2)) ? 2 : 1;
            $player = new Player($user->player_id, ($user->team ? $user->team : $currentTeam));
            $players[] = $player;
            $game->addPlayer($player);
            $playerNum++;
        }
        $game->createLocations();
        $configs = \Config::get('tcg.cards');
        foreach ($users as $user) {
            foreach ($user->deck->cards as $card) {
                $game->setUpCard(
                    Card::createFromConfig($configs[$card->card_id], $game, $card->id),
                    $user->player_id
                );
            }
            $game->setUpCard(
                Card::createFromConfig($configs[$user->deck->king->card_id], $game, $user->deck->king->id),
                $user->player_id
            );
        }
        $game->init();
        $game->start();
        $game->gameAutoActions();
        return $game;
    }

    public static function buildTest($currentPlayerId, $config)
    {

        $player1 = new Player($currentPlayerId, 1);
        $player2 = new Player(2, 2);
        if (!empty($config['isBot'])) {
            $player2->type = Player::PLAYER_TYPE_BOT;
        }

        $game      = new Game($currentPlayerId);
        $game->log = new GameLog($game);
        $game->gameType = Game::GAME_TYPE_TEST;
        if (!empty($config['debug'])) {
            $game->gameType = Game::GAME_TYPE_DEBUG;
        }
        $game->setUpGameObject();
        $game->sessionType = Game::IMPORT_TYPE_NORMAL;

        $game->addPlayer($player1);
        $game->addPlayer($player2);
        $game->createLocations();

        $configs = \Config::get('tcg.cards');
        $deck1 = [
            [Card::createFromConfig($configs[1], $game), 2],
            [Card::createFromConfig($configs[2], $game), 2],
            [Card::createFromConfig($configs[3], $game), 2],
            [Card::createFromConfig($configs[4], $game), 2],
            [Card::createFromConfig($configs[5], $game), 2],
            [Card::createFromConfig($configs[6], $game), 2],
            [Card::createFromConfig($configs[8], $game), 2],
            [Card::createFromConfig($configs[9], $game), 2],
            [Card::createFromConfig($configs[7], $game), 1],
        ];
        $deck2 = [
            [Card::createFromConfig($configs[50], $game), 2],
            [Card::createFromConfig($configs[51], $game), 2],
            [Card::createFromConfig($configs[52], $game), 2],
            [Card::createFromConfig($configs[53], $game), 2],
            [Card::createFromConfig($configs[54], $game), 2],
            [Card::createFromConfig($configs[55], $game), 2],
            [Card::createFromConfig($configs[56], $game), 2],
            [Card::createFromConfig($configs[57], $game), 2],
            [Card::createFromConfig($configs[59], $game), 1],
        ];
        foreach ($deck2 as $card) {
            for($i = 0; $i < $card[1]; $i++) {
                $game->setUpCard(clone $card[0], $player1->id);
            }
        }
        foreach ($deck1 as $card) {
            for($i = 0; $i < $card[1]; $i++) {
                $game->setUpCard(clone $card[0], $player2->id);
            }
        }
        $game->init();
        $game->start();
        $game->gameAutoActions();
        return $game;
    }

    public static function buildSituation($currentPlayerId, $situation, $config)
    {
        $situation = [
            'cards' => [
                [
                    'id'    => 54,
                    'owner' => 1,
                    'x' => 4,
                    'y' => 6,
                    'currentHealth' => 10,
                    'isKing' => true,
                    'armor' => 12,
                    'maxArmor' => 14,
                    'maxHealth' => 10,
                    'keywords' => ['focus'],
                    'isCurrent' => true,
                ],
                [
                    'id'    => 57,
                    'owner' => 1,
                    'x' => 3,
                    'y' => 6,
                    'currentHealth' => 5,
                ],
                [
                    'id'    => 3,
                    'owner' => 2,
                    'x' => 4,
                    'y' => 6,
                    'isKing' => true,
                    'attack' => [5, 5],
                    'currentHealth' => 200,
                    'keywords' => ['focus'],
                    'maxHealth' => 99
                ],
            ],
            'hands' => [
                1 => [56, 54, 4, 8],
            ],
            'decks' => [
                1 => [51, 52, 54],
            ],
            'playerTurnId' => 1,
        ];

        $game      = new Game($currentPlayerId);
        $game->log = new GameLog($game);
        $game->sessionType = Game::IMPORT_TYPE_NORMAL;
        $game->gameType = Game::GAME_TYPE_TEST;
        $game->setUpGameObject();

        $player1 = new Player($currentPlayerId, 1);
        $player2 = new Player(2, 2);
        if (!empty($config['isBot'])) {
            $player2->type = Player::PLAYER_TYPE_BOT;
        }

        $game->addPlayer($player1);
        $game->addPlayer($player2);
        $game->createLocations();
        $game->playerTurnId = $situation['playerTurnId'];

        $configs = \Config::get('tcg.cards');
        foreach ($situation['cards'] as $cardData) {
            $cardConfig = $configs[$cardData['id']];
            if (!empty($cardData['isKing'])) {
                $cardConfig['isKing'] = true;
                unset($cardData['isKing']);
            }
            $card = Card::createFromConfig($cardConfig, $game);
            $card->init();
            $card->unit->deploy();
            $game->setUpCard($card, $cardData['owner']);
            $x = $cardData['x'];
            $y = $cardData['y'];
            list($x, $y) = $game->convertCoordinats($x, $y, $card->owner);
            //var_dump($card); die;
            $card->unit->x = $x;
            $card->unit->y = $y;
            $game->moveCards([$card], Game::LOCATION_HAND, Game::LOCATION_FIELD);
            $keys = ['currentHealth', 'armor', 'maxArmor' , 'keywords', 'attack', 'maxHealth', 'moveSteps', 'moveType'];
            foreach ($keys as $keyName) {
                if (isset($cardData[$keyName])) {
                    $card->unit->{$keyName} = $cardData[$keyName];
                }
            }
            if (isset($cardData['isCurrent'])) {
                $game->currentCardId = $card->id;
            }
        }
        if (!empty($situation['hands'])) {
            foreach ($situation['hands'] as $playerId => $cards) {
                foreach ($cards as $cardId) {
                    $cardConfig = $configs[$cardId];
                    $card = Card::createFromConfig($cardConfig, $game);
                    $card->init();
                    $game->setUpCard($card, $playerId);
                    $game->moveCards([$card], Game::LOCATION_DECK, Game::LOCATION_HAND);
                }
            }
        }
        if (!empty($situation['decks'])) {
            foreach ($situation['decks'] as $playerId => $cards) {
                foreach ($cards as $cardId) {
                    $cardConfig = $configs[$cardId];
                    $card = Card::createFromConfig($cardConfig, $game);
                    $game->setUpCard($card, $playerId);
                }
            }
        }
        $game->init();
        $game->phase = Game::PHASE_BATTLE;
        $game->start();
        $game->gameAutoActions();
        return $game;
    }
}
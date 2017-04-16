<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Entity\Maze\MazeObject;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiController
 *
 * @package AppBundle\Controller
 * @Route("/api")
 */
class ApiController extends Controller
{
    const NAME = 'Dominator API';

    /**
     * Return the name of the API
     *
     * @Route("/", name="api_home")
     * @return JsonResponse
     */
    public function indexAction()
    {
        return new JsonResponse(static::NAME);
    }

    /**
     * Move the player
     *
     * @Route("/move", name="api_move")
     * @param Request $request
     * @return JsonResponse
     */
    public function moveAction(Request $request)
    {
        // Get the data form the request
        $body = $request->getContent();
        $data = json_decode($body);

        // Extract some vars
        $uuid = $data->player->id;
        $walls = $data->maze->walls;
        $height = $data->maze->size->height;
        $width = $data->maze->size->width;
        $pos = $data->player->position;
        $prev = $data->player->previous;
        $goal = $data->maze->goal;

        // Compute current direction
        $dir = null;
        if ($pos->y < $prev->y) {
            $dir = MazeObject::DIRECTION_UP;
        } elseif ($pos->y > $prev->y) {
            $dir = MazeObject::DIRECTION_DOWN;
        } elseif ($pos->x < $prev->x) {
            $dir = MazeObject::DIRECTION_LEFT;
        } elseif ($pos->x > $prev->x) {
            $dir = MazeObject::DIRECTION_RIGHT;
        } else {
            $dir = MazeObject::DIRECTION_UP;
        }

        // Get data from session
        $savedData = $this->readFile($uuid);
        if ($savedData) {
            $savedData = json_decode($savedData, false);
            $iter = $savedData->iter;
            $maze = $savedData->maze;
        } else {
            $iter = 1;
            $maze = array();
            for ($y = 0; $y < $height; ++$y) {
                $maze[$y] = array();
                for ($x = 0; $x < $width; ++$x) {
                    $maze[$y][$x] = 0;
                }
            }
        }

        // Add visible walls to the maze
        foreach ($walls as $wall) {
            $maze[$wall->y][$wall->x] = -1;
        }

        // Saving current iteration
        if ($maze[$pos->y][$pos->x] == 0) {
            $maze[$pos->y][$pos->x] = $iter;
        }

        // Compute the next direction
        $dir = $this->findNextMove($maze, $pos, $dir, $goal);

//        echo PHP_EOL;
//        foreach ($maze as $y => $row) {
//            foreach ($row as $x => $cell) {
//                echo ($cell < 0) ? 'X' : (($y == $pos->y && $x == $pos->x) ? 'P' : ($cell > 0 ? '.' : ' '));
//            }
//            echo PHP_EOL;
//        }

        $savedData = new \stdClass();
        $savedData->iter = 1 + $iter;
        $savedData->maze = $maze;
        $this->writeFile($uuid, json_encode($savedData));

        $result = new \stdClass();
        $result->name = static::NAME;
        $result->move = $dir;
        $response = new JsonResponse($result);
        return $response;
    }

    /**
     * Computes the next movement
     *
     * @param array $maze
     * @param \stdClass $pos
     * @param string $dir
     * @param \stdClass $goal
     * @return string
     */
    private function findNextMove($maze, $pos, $dir, $goal)
    {
        // Array of movements
        $moves = array(
            MazeObject::DIRECTION_UP,
            MazeObject::DIRECTION_RIGHT,
            MazeObject::DIRECTION_DOWN,
            MazeObject::DIRECTION_LEFT
        );

        $rightDir = $moves[(array_search($dir, $moves) + 1) % 4];
        $leftDir = $moves[(array_search($dir, $moves) + 3) % 4];
        $backDir = $moves[(array_search($dir, $moves) + 2) % 4];

        $forwardPos = $this->nextPosition($pos, $dir);
        $rightPos = $this->nextPosition($pos, $rightDir);
        $leftPos = $this->nextPosition($pos, $leftDir);
        $backPos = $this->nextPosition($pos, $backDir);

        // If the goal is at a side, move to it
        if ($forwardPos->y == $goal->y && $forwardPos->x == $goal->x) {
            return $dir;
        }

        if ($rightPos->y == $goal->y && $rightPos->x == $goal->x) {
            return $rightDir;
        }

        if ($leftPos->y == $goal->y && $leftPos->x == $goal->x) {
            return $leftDir;
        }

        if ($backPos->y == $goal->y && $backPos->x == $goal->x) {
            return $backDir;
        }

        // Go forward if possible
        $forwardContent= $maze[$forwardPos->y][$forwardPos->x];
        if ($forwardContent == 0) {
            return $dir;
        }

        // Turn right or left if possible (random)
        $rightContent= $maze[$rightPos->y][$rightPos->x];
        $leftContent= $maze[$leftPos->y][$leftPos->x];

        if (0 == rand(0, 1)) {
            if ($rightContent == 0) {
                return $rightDir;
            }
            if ($leftContent == 0) {
                return $leftDir;
            }
        } else {
            if ($leftContent == 0) {
                return $leftDir;
            }
            if ($rightContent == 0) {
                return $rightDir;
            }
        }

        // Else: go back
        $backContent= $maze[$backPos->y][$backPos->x];
        $currentContent= $maze[$pos->y][$pos->x];

        $moves = array();
        if ($forwardContent > 0 && $forwardContent < $currentContent) {
            $moves[$forwardContent] = $dir;
        }

        if ($rightContent > 0 && $rightContent < $currentContent) {
            $moves[$rightContent] = $rightDir;
        }

        if ($leftContent > 0 && $leftContent < $currentContent) {
            $moves[$leftContent] = $leftDir;
        }

        if ($backContent > 0 && $backContent < $currentContent) {
            $moves[$backContent] = $backDir;
        }

        ksort($moves, SORT_NUMERIC);
        $moves = array_reverse($moves);
        return reset($moves);
    }

    /**
     * Computes the next position
     *
     * @param \stdClass $pos
     * @param string $dir
     * @return \stdClass
     */
    private function nextPosition($pos, $dir)
    {
        $new = clone $pos;
        switch ($dir) {
            case MazeObject::DIRECTION_UP:
                --$new->y;
                break;

            case MazeObject::DIRECTION_DOWN:
                ++$new->y;
                break;

            case MazeObject::DIRECTION_LEFT:
                --$new->x;
                break;

            case MazeObject::DIRECTION_RIGHT:
                ++$new->x;
                break;
        }
        return $new;
    }

    /**
     * Reads the temporary file with the saved data
     *
     * @param string $uuid
     * @return string|false
     */
    private function readFile($uuid)
    {
        $filename = sys_get_temp_dir() . '/' . $uuid . '.json';
        $handler = @fopen($filename, 'rb');
        if (!$handler) {
            return false;
        }

        $data = @fgets($handler);
        if (!$data) {
            return false;
        }

        return $data;
    }

    /**
     * Writes the process data to a temporary file
     *
     * @param string $uuid
     * @param string $data
     * @return bool
     */
    private function writeFile($uuid, $data)
    {
        $filename = sys_get_temp_dir() . '/' . $uuid . '.json';
        $handler = @fopen($filename, 'wb');
        if (!$handler) {
            return false;
        }

        fwrite($handler, $data);
        fclose($handler);
        return true;
    }
}

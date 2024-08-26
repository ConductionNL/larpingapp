<?php

namespace OCA\LarpingApp\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use OCA\opencatalogi\lib\Db\Player;
use OCA\OpenCatalogi\Db\PlayerMapper;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IAppConfig;
use OCP\IRequest;

class PlayersController extends Controller
{
    const TEST_ARRAY = [
        [
            "id" => "5137a1e5-b54d-43ad-abd1-4b5bff5fcd3f",
            "name" => "Player 1",
            "description" => "summary for one"
        ],
        [
            "id" => "4c3edd34-a90d-4d2a-8894-adb5836ecde8",
            "name" => "Player 2",
            "description" => "summary for two"
        ],
        [
            "id" => "15551d6f-44e3-43f3-a9d2-59e583c91eb0",
            "name" => "Player 3",
            "description" => "summary for three"
        ],
        [
            "id" => "0a3a0ffb-dc03-4aae-b207-0ed1502e60da",
            "name" => "Player 4",
            "description" => "summary for four"
        ]
    ];

    /**
     * Constructor for the PlayersController
     *
     * @param string $appName The name of the app
     * @param IRequest $request The request object
     * @param IAppConfig $config The app configuration object
     */
    public function __construct(
        $appName,
        IRequest $request,
        private readonly IAppConfig $config
    )
    {
        parent::__construct($appName, $request);
    }

    /**
     * Returns the template of the main app's page
     * 
     * This method renders the main page of the application, adding any necessary data to the template.
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @return TemplateResponse The rendered template response
     */
    public function page(): TemplateResponse
    {           
        return new TemplateResponse(
            'larpingapp',
            'index',
            []
        );
    }
    
    /**
     * Retrieves a list of all players
     * 
     * This method returns a JSON response containing an array of all players in the system.
     * Currently, it uses a test array instead of fetching from a database.
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @return JSONResponse A JSON response containing the list of players
     */
    public function index(): JSONResponse
    {
        $results = ["results" => self::TEST_ARRAY];
        return new JSONResponse($results);
    }

    /**
     * Retrieves a single player by their ID
     * 
     * This method returns a JSON response containing the details of a specific player.
     * Currently, it fetches the player from a test array instead of a database.
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $id The ID of the player to retrieve
     * @return JSONResponse A JSON response containing the player details
     */
    public function show(string $id): JSONResponse
    {
        $result = self::TEST_ARRAY[$id];
        return new JSONResponse($result);
    }

    /**
     * Creates a new player
     * 
     * This method is intended to create a new player based on POST data.
     * Currently, it returns an empty JSON response as a placeholder.
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @return JSONResponse An empty JSON response (placeholder)
     */
    public function create(): JSONResponse
    {
        // get post from requests
        return new JSONResponse([]);
    }

    /**
     * Updates an existing player
     * 
     * This method is intended to update an existing player based on their ID.
     * Currently, it returns the player from the test array without actually updating it.
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $id The ID of the player to update
     * @return JSONResponse A JSON response containing the (unchanged) player details
     */
    public function update(string $id): JSONResponse
    {
        $result = self::TEST_ARRAY[$id];
        return new JSONResponse($result);
    }

    /**
     * Deletes a player
     * 
     * This method is intended to delete a player based on their ID.
     * Currently, it returns an empty JSON response as a placeholder.
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     *
     * @param string $id The ID of the player to delete
     * @return JSONResponse An empty JSON response (placeholder)
     */
    public function destroy(string $id): JSONResponse
    {
        return new JSONResponse([]);
    }
}

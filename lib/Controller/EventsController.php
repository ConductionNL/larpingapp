<?php

namespace OCA\LarpingApp\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use OCA\opencatalogi\lib\Db\Event;
use OCA\OpenCatalogi\Db\EventMapper;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IAppConfig;
use OCP\IRequest;

class EventsController extends Controller
{
    const TEST_ARRAY = [
        [
            "id" => "5137a1e5-b54d-43ad-abd1-4b5bff5fcd3f",
            "name" => "Summer Solstice Celebration",
            "description" => "A magical gathering to honor the longest day of the year",
            "characters" => [
                ["$ref" => "https://larping.nl/character.schema.json"],
                ["$ref" => "https://larping.nl/character.schema.json"]
            ],
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "startDate" => "2023-06-21T18:00:00Z",
            "endDate" => "2023-06-22T06:00:00Z",
            "location" => "Enchanted Forest Clearing"
        ],
        [
            "id" => "4c3edd34-a90d-4d2a-8894-adb5836ecde8",
            "name" => "Dragon's Lair Expedition",
            "description" => "A perilous journey to retrieve a legendary artifact",
            "characters" => [
                ["$ref" => "https://larping.nl/character.schema.json"],
                ["$ref" => "https://larping.nl/character.schema.json"],
                ["$ref" => "https://larping.nl/character.schema.json"]
            ],
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"],
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "startDate" => "2023-07-15T09:00:00Z",
            "endDate" => "2023-07-16T17:00:00Z",
            "location" => "Misty Mountains"
        ],
        [
            "id" => "15551d6f-44e3-43f3-a9d2-59e583c91eb0",
            "name" => "Royal Masquerade Ball",
            "description" => "An elegant evening of mystery and intrigue at the royal palace",
            "characters" => [
                ["$ref" => "https://larping.nl/character.schema.json"],
                ["$ref" => "https://larping.nl/character.schema.json"],
                ["$ref" => "https://larping.nl/character.schema.json"],
                ["$ref" => "https://larping.nl/character.schema.json"]
            ],
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"],
                ["$ref" => "https://larping.nl/effect.schema.json"],
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "startDate" => "2023-08-01T20:00:00Z",
            "endDate" => "2023-08-02T02:00:00Z",
            "location" => "Royal Palace Grand Ballroom"
        ]
    ];

    public function __construct(
		$appName,
		IRequest $request,
		private readonly IAppConfig $config
	)
    {
        parent::__construct($appName, $request);
    }

	/**
	 * This returns the template of the main app's page
	 * It adds some data to the template (app version)
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @return TemplateResponse
	 */
	public function page(): TemplateResponse
	{			
        return new TemplateResponse(
            //Application::APP_ID,
            'larpingapp',
            'index',
            []
        );
	}
	

    /**
     * Return (and serach) all objects
     * 
     * @NoAdminRequired
     * @NoCSRFRequired
	 *
	 * @return JSONResponse
     */
    public function index(): JSONResponse
    {
        $results = ["results" => self::TEST_ARRAY];
        return new JSONResponse($results);
    }

    /**
     * Read a single object
     * 
     * @NoAdminRequired
     * @NoCSRFRequired
	 *
	 * @return JSONResponse
     */
    public function show(string $id): JSONResponse
    {
        $result = self::TEST_ARRAY[$id];
        return new JSONResponse($result);
    }


    /**
     * Creatue an object
     * 
     * @NoAdminRequired
     * @NoCSRFRequired
	 *
	 * @return JSONResponse
     */
    public function create(): JSONResponse
    {
        // get post from requests
        return new JSONResponse([]);
    }

    /**
     * Update an object
     * 
     * @NoAdminRequired
     * @NoCSRFRequired
	 *
	 * @return JSONResponse
     */
    public function update(string $id): JSONResponse
    {
        $result = self::TEST_ARRAY[$id];
        return new JSONResponse($result);
    }

    /**
     * Delate an object
     * 
     * @NoAdminRequired
     * @NoCSRFRequired
	 *
	 * @return JSONResponse
     */
    public function destroy(string $id): JSONResponse
    {
        return new JSONResponse([]);
    }
}
<?php

namespace OCA\LarpingApp\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use OCA\opencatalogi\lib\Db\Ability;
use OCA\OpenCatalogi\Db\AbilityMapper;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IAppConfig;
use OCP\IRequest;

class AbilitiesController extends Controller
{
    const TEST_ARRAY = [
        [
            "id" => "56cf6db0-7c37-41a5-968b-d322c3f0da28",
            "name" => "Experience points",
            "description" => "An experience point is a unit of measurement used in some tabletop role-playing games and role-playing video games to quantify a player character's life experience and progression through the game. Experience points are generally awarded for the completion of missions, overcoming obstacles and opponents, and for successful role-playing.",
            "base" => 0
        ],
        [
            "id" => "4c3edd34-a90d-4d2a-8894-adb5836ecde8",
            "name" => "Strength",
            "description" => "Represents the physical power and muscle of a character. It affects melee attack rolls, damage rolls for melee weapons, and various strength-based skill checks.",
            "base" => 10
        ],
        [
            "id" => "15551d6f-44e3-43f3-a9d2-59e583c91eb0",
            "name" => "Mana",
            "description" => "Represents the magical energy or power that a character can use to cast spells or perform magical abilities. It is often depleted when using magic and regenerates over time or through rest.",
            "base" => 20
        ],
        [
            "id" => "0a3a0ffb-dc03-4aae-b207-0ed1502e60da",
            "name" => "Hit Points",
            "description" => "Represents the amount of damage a character can sustain before falling unconscious or dying. It's often calculated based on other stats like Constitution.",
            "base" => 15
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
            //Application::APP_ID,
            'larpingapp',
            'index',
            []
        );
	}
	

    /**
     * Retrieves a list of all abilities
     * 
     * This method returns a JSON response containing an array of all abilities in the system.
     * Currently, it uses a test array instead of fetching from a database.
     *
     * @NoAdminRequired
     * @NoCSRFRequired
	 *
	 * @return JSONResponse A JSON response containing the list of abilities
     */
    public function index(): JSONResponse
    {
        $results = ["results" => self::TEST_ARRAY];
        return new JSONResponse($results);
    }

    /**
     * Retrieves a single ability by its ID
     * 
     * This method returns a JSON response containing the details of a specific ability.
     * Currently, it fetches the ability from a test array instead of a database.
     *
     * @NoAdminRequired
     * @NoCSRFRequired
	 *
	 * @param string $id The ID of the ability to retrieve
	 * @return JSONResponse A JSON response containing the ability details
     */
    public function show(string $id): JSONResponse
    {
        $result = self::TEST_ARRAY[$id];
        return new JSONResponse($result);
    }


    /**
     * Creates a new ability
     * 
     * This method is intended to create a new ability based on POST data.
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
     * Updates an existing ability
     * 
     * This method is intended to update an existing ability based on its ID.
     * Currently, it returns the ability from the test array without actually updating it.
     *
     * @NoAdminRequired
     * @NoCSRFRequired
	 *
	 * @param string $id The ID of the ability to update
	 * @return JSONResponse A JSON response containing the (unchanged) ability details
     */
    public function update(string $id): JSONResponse
    {
        $result = self::TEST_ARRAY[$id];
        return new JSONResponse($result);
    }

    /**
     * Deletes an ability
     * 
     * This method is intended to delete an ability based on its ID.
     * Currently, it returns an empty JSON response as a placeholder.
     *
     * @NoAdminRequired
     * @NoCSRFRequired
	 *
	 * @param string $id The ID of the ability to delete
	 * @return JSONResponse An empty JSON response (placeholder)
     */
    public function destroy(string $id): JSONResponse
    {
        return new JSONResponse([]);
    }
}
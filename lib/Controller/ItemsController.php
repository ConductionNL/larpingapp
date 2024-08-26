<?php

namespace OCA\LarpingApp\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use OCA\opencatalogi\lib\Db\Item;
use OCA\OpenCatalogi\Db\ItemMapper;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IAppConfig;
use OCP\IRequest;

class ItemsController extends Controller
{
    const TEST_ARRAY = [
        [
            "id" => "5137a1e5-b54d-43ad-abd1-4b5bff5fcd3f",
            "name" => "Hand of Vecna",
            "description" => "The Hand of Vecna is a powerful artifact in many campaign settings for the Dungeons & Dragons fantasy role-playing game. Originating in the World of Greyhawk campaign setting, the Hand appears as a severed left human hand, blackened and charred, with long, claw-like fingernails.",
            "effect" => "The new bearer of the Eye or Hand (or both) will gain access to powerful spell-like abilities, but the items will slowly corrupt them, turning them evil over time",
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "unique" => true,
            "characters" => [
                ["$ref" => "https://larping.nl/character.schema.json"]
            ]
        ],
        [
            "id" => "4c3edd34-a90d-4d2a-8894-adb5836ecde8",
            "name" => "Vorpal Sword",
            "description" => "A legendary sword known for its ability to sever the heads of opponents. It appears as a finely crafted longsword with an impossibly sharp edge that seems to shimmer with an otherworldly energy.",
            "effect" => "On a critical hit, this sword has a chance to instantly decapitate the target. It also grants the wielder enhanced combat prowess and reflexes.",
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"],
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "unique" => true,
            "characters" => [
                ["$ref" => "https://larping.nl/character.schema.json"]
            ]
        ],
        [
            "id" => "15551d6f-44e3-43f3-a9d2-59e583c91eb0",
            "name" => "Cloak of Invisibility",
            "description" => "A shimmering cloak that seems to be made of woven moonlight. When worn, it has the power to render the wearer completely invisible to the naked eye.",
            "effect" => "Grants the wearer invisibility at will. However, prolonged use may cause a growing detachment from the visible world and potential madness.",
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "unique" => true,
            "characters" => [
                ["$ref" => "https://larping.nl/character.schema.json"]
            ]
        ],
        [
            "id" => "0a3a0ffb-dc03-4aae-b207-0ed1502e60da",
            "name" => "Potion of Healing",
            "description" => "A small vial containing a swirling red liquid that glows faintly. When consumed, it rapidly heals wounds and restores vitality.",
            "effect" => "Instantly heals 2d4+2 hit points when consumed. Can be used in combat as a bonus action.",
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "unique" => false,
            "characters" => []
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

<?php

namespace OCA\LarpingApp\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use OCA\opencatalogi\lib\Db\Skill;
use OCA\OpenCatalogi\Db\SkillMapper;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IAppConfig;
use OCP\IRequest;

class SkillsController extends Controller
{
    const TEST_ARRAY = [
        [
            "id" => "5137a1e5-b54d-43ad-abd1-4b5bff5fcd3f",
            "name" => "Healing LvL 1",
            "description" => "Healers are what keeps the party going, when someone goes down a healer makes sure they get back up again.",
            "effect" => "Character has access to level 1 healing spells",
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "requiredSkills" => [],
            "requiredStats" => [
                ["$ref" => "https://larping.nl/stat.schema.json"]
            ],
            "requiredConditions" => [],
            "requiredEffects" => [],
            "requiredScore" => 10
        ],
        [
            "id" => "4c3edd34-a90d-4d2a-8894-adb5836ecde8",
            "name" => "Swordsmanship",
            "description" => "Masters of the blade, swordsmen are deadly in close combat and can turn the tide of battle.",
            "effect" => "Character gains +2 to attack rolls with swords",
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "requiredSkills" => [],
            "requiredStats" => [
                ["$ref" => "https://larping.nl/stat.schema.json"]
            ],
            "requiredConditions" => [],
            "requiredEffects" => [],
            "requiredScore" => 12
        ],
        [
            "id" => "15551d6f-44e3-43f3-a9d2-59e583c91eb0",
            "name" => "Arcane Knowledge",
            "description" => "Those versed in arcane knowledge can unravel the mysteries of magic and harness its power.",
            "effect" => "Character can identify magical items and effects",
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "requiredSkills" => [],
            "requiredStats" => [
                ["$ref" => "https://larping.nl/stat.schema.json"]
            ],
            "requiredConditions" => [],
            "requiredEffects" => [],
            "requiredScore" => 14
        ],
        [
            "id" => "0a3a0ffb-dc03-4aae-b207-0ed1502e60da",
            "name" => "Stealth",
            "description" => "Masters of stealth can move unseen and unheard, perfect for scouting or ambush.",
            "effect" => "Character gains advantage on stealth checks",
            "effects" => [
                ["$ref" => "https://larping.nl/effect.schema.json"]
            ],
            "requiredSkills" => [],
            "requiredStats" => [
                ["$ref" => "https://larping.nl/stat.schema.json"]
            ],
            "requiredConditions" => [],
            "requiredEffects" => [],
            "requiredScore" => 13
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

<?php

namespace OCA\LarpingApp\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use OCA\opencatalogi\lib\Db\Condition;
use OCA\OpenCatalogi\Db\ConditionMapper;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IAppConfig;
use OCP\IRequest;

class ConditionsController extends Controller
{
    const TEST_ARRAY = [
        [
            "id" => "5137a1e5-b54d-43ad-abd1-4b5bff5fcd3f",
            "name" => "Vampiric Demeanor",
            "description" => "You crave the blood of other humanoid beings and feel powerful when drinking it",
            "effect" => "Character may drink the blood of other humanoid beings, dealing 1 HP damage per 10 seconds of drinking. Character gains 1 Spiritual Mana per damage dealt. Character sustains 1 HP damage cumulative for each 24 hours in which character has not used this ability. E.g., 1 HP for day one, 2 HP on day 3",
            "effects" => [
                {
                    "type": "damage",
                    "value": 1,
                    "target": "other",
                    "frequency": "per_10_seconds"
                },
                {
                    "type": "gain",
                    "value": 1,
                    "target": "self",
                    "stat": "Spiritual Mana",
                    "frequency": "per_damage_dealt"
                },
                {
                    "type": "damage",
                    "value": 1,
                    "target": "self",
                    "frequency": "per_24_hours_inactive",
                    "cumulative": true
                }
            ],
            "unique" => false,
            "characters" => []
        ],
        [
            "id" => "4c3edd34-a90d-4d2a-8894-adb5836ecde8",
            "name" => "Lycanthropy",
            "description" => "You transform into a werewolf during full moons, gaining enhanced strength but losing control",
            "effect" => "During full moons, character gains +5 to Strength but must make a Will save (DC 15) every hour or attack the nearest creature. Transformation lasts for 8 hours.",
            "effects" => [
                {
                    "type": "buff",
                    "stat": "Strength",
                    "value": 5,
                    "duration": "8_hours",
                    "trigger": "full_moon"
                },
                {
                    "type": "save",
                    "stat": "Will",
                    "dc": 15,
                    "frequency": "hourly",
                    "failure_effect": "attack_nearest"
                }
            ],
            "unique" => false,
            "characters" => []
        ],
        [
            "id" => "15551d6f-44e3-43f3-a9d2-59e583c91eb0",
            "name" => "Cursed Knowledge",
            "description" => "You possess forbidden knowledge that slowly corrupts your mind",
            "effect" => "Character gains +3 to all Knowledge checks but must make a Wisdom save (DC 12) daily or lose 1 Sanity point. If Sanity reaches 0, character becomes an NPC.",
            "effects" => [
                {
                    "type": "buff",
                    "stat": "Knowledge",
                    "value": 3,
                    "applies_to": "all_checks"
                },
                {
                    "type": "save",
                    "stat": "Wisdom",
                    "dc": 12,
                    "frequency": "daily",
                    "failure_effect": "lose_sanity",
                    "failure_value": 1
                }
            ],
            "unique" => true,
            "characters" => []
        ],
        [
            "id" => "0a3a0ffb-dc03-4aae-b207-0ed1502e60da",
            "name" => "Fey Touched",
            "description" => "You've been blessed (or cursed) by the fey, granting you magical abilities but making you vulnerable to iron",
            "effect" => "Character can cast Charm Person once per day without using a spell slot, but takes 1d4 damage when touching iron objects.",
            "effects" => [
                {
                    "type": "spell",
                    "name": "Charm Person",
                    "uses": 1,
                    "frequency": "daily",
                    "cost": "free"
                },
                {
                    "type": "vulnerability",
                    "trigger": "touch_iron",
                    "damage": "1d4"
                }
            ],
            "unique" => false,
            "characters" => []
        }
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

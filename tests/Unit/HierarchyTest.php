<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class HierarchyTest extends TestCase
{

    /**
     * Regular request with regular hierarchy
     */
    private $regularArray1 = [
        "Nick" => "Sophie",
        "Sophie" => "Jonas",
        "Pete" => "Nick",
        "Barbara" => "Nick",
        "John" => "Pete",
    ];

    private $regularResponse1 = [
        "Jonas" => [
            "Sophie" => [
                "Nick" => [
                    "Pete" => [
                        "John" => [],
                    ],
                    "Barbara" => []
                ]
            ]
        ]
    ];

    /**
     * Regular request with regular hierarchy (pdf test case)
     */
    private $regularArray2 = [
        "Pete" => "Nick",
        "Barbara" => "Nick",
        "Nick" => "Sophie",
        "Sophie" => "Jonas",
    ];

    private $regularResponse2 = [
        "Jonas" => [
            "Sophie" => [
                "Nick" => [
                    "Pete" => [],
                    "Barbara" => []
                ]
            ]
        ]
    ];

    /**
     * Two bosses hierarchy (invalid)
     */
    private $twoBossesArray = [
        "Nick" => "Sophie",
        "Sophie" => "Jonas",
        "Pete" => "Nick",
        "Barbara" => "Nick",
        "John" => "Papa",
    ];

    /**
     * Loop hierarchy (invalid)
     */
    private $loopArray = [
        "Nick" => "Sophie",
        "Pete" => "Nick",
        "Sophie" => "Nick",
        "Barbara" => "Nick",
    ];

    /**
     * Unit tests the function responsible for building the hierarchical tree
     * based on the given array.
     */
    public function testHierarchyFormattingFunction()
    {
        // testing a regular case
        $tree = User::formatHierarchy($this->regularArray1);
        $this->assertEquals(serialize($tree), serialize($this->regularResponse1));

        // testing the case on the test's pdf
        $tree = User::formatHierarchy($this->regularArray2);
        $this->assertEquals(serialize($tree), serialize($this->regularResponse2));

        // testing when the given array is empty
        $tree = User::formatHierarchy([]);
        $this->assertEquals(serialize($tree), serialize([]));
    }

    /**
     * Tests the hierarchical tree building requests
     *
     * @return void
     */
    public function testHierarchyFormattingRequest()
    {
        // testing the regular scenario
        $this->json('POST', '/api/hierarchy', $this->regularArray1)
            ->assertJson($this->regularResponse1)
            ->assertStatus(200);

        // testing for invalid hierarchy with more than one boss
        $this->json('POST', '/api/hierarchy', $this->twoBossesArray)->assertStatus(422);

        // testing for invalid hierarchy with employee loop
        $this->json('POST', '/api/hierarchy', $this->loopArray)->assertStatus(422);
    }
}

<?php
/**
 * Counts the number of connected subgraphs in a graph.
 *
 * @param array $V Array of vertices.
 * @param callable $f Function that determines if there is an edge between two vertices.
 * @return int Number of connected subgraphs.
 */
function countConnectedSubgraphs(array $V, callable $f): int {
    // Initialize the count of subgraphs and the visited array.
    $subgraphsCount = 0;
    $visited = array_fill_keys($V, false);

    // Iterate over all vertices and perform a DFS on each unvisited vertex.
    foreach ($V as $vertex) {
        if (!$visited[$vertex]) {
            $subgraph = [];
            depthFirstSearch($V, $f, $vertex, $visited, $subgraph); 
            $subgraphsCount++;
        }
    }

    return $subgraphsCount;
}

/**
 * Performs a depth-first search on a graph.
 *
 * @param array $V Array of vertices.
 * @param callable $f Function that determines if there is an edge between two vertices.
 * @param string $currentVertex The current vertex to start the search from.
 * @param array $visited Array of visited vertices.
 * @param array $subgraph Array of vertices in the subgraph.
 * @return void
 */
function depthFirstSearch(array $V, callable $f, string $currentVertex, &$visited, &$subgraph): void {
    // Sets the current vertex as visited and adds it to the subgraph. 
    // We are using references here to avoid copying the arrays.
    $visited[$currentVertex] = true;
    $subgraph[] = $currentVertex;

    // The function calls itself recursively for each unvisited neighboring vertex.
    foreach ($V as $vertex) {
        if ($currentVertex !== $vertex && $f($currentVertex, $vertex) && !$visited[$vertex]) {
            depthFirstSearch($V, $f, $vertex, $visited, $subgraph);
        }
    }
}

/**
 * Helper function to echo the result of a test.
 *
 * @param mixed $result Result of the test.
 * @param mixed $expected Expected result of the test.
 */
function echoResult($result, $expected): void {
    if ($result === $expected) {
        echo "\033[32mTest passed\033[0m\n"; 
    } else {
        echo "\033[31mTest failed\033[0m\n"; 
    }
}

/**
 * Tests the function.
 *
 */
function runTests(): void {
    echo "Running tests \n";

    echo "Test 1: Single vertex graph\n";
    echo "Graph: A\n";
    echo "Expected: 1\n";
    $V = ['A'];
    $f = function($v1, $v2) { return false; };
    echoResult(countConnectedSubgraphs($V, $f), 1);

    echo "---\n";

    echo "Test 2: Two vertices, one edge\n";
    echo "Graph: A - B\n";
    $V = ['A', 'B'];
    $f = function($v1, $v2) { return $v1 === 'A' && $v2 === 'B'; };
    echoResult(countConnectedSubgraphs($V, $f), 1);

    echo "---\n";

    echo "Test 3: Two connected subgraphs\n";
    echo "Graph: A - B - C, D - E\n";
    echo "Expected: 2\n";
    $V = ['A', 'B', 'C', 'D', 'E'];
    $f = function($v1, $v2) {
        return ($v1 === 'A' && $v2 === 'B') ||
               ($v1 === 'B' && $v2 === 'C') ||
               ($v1 === 'D' && $v2 === 'E');
    };
    echoResult(countConnectedSubgraphs($V, $f), 2);

    echo "---\n";

    echo "Test 4: Complex graph\n";
    echo "Graph: A - B - C, D - E, F - G\n";
    echo "Expected: 3\n";
    $V = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
    $f = function($v1, $v2) {
        return ($v1 === 'A' && $v2 === 'B') ||
               ($v1 === 'B' && $v2 === 'C') ||
               ($v1 === 'D' && $v2 === 'E') ||
               ($v1 === 'F' && $v2 === 'G');
    };
    echoResult(countConnectedSubgraphs($V, $f), 3);
}




runTests();
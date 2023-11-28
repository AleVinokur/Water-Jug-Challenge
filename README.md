# Water Jug Challenge

This project solves the Water Jug Challenge, allowing you to find the optimal solution to measure a specific amount of water using two jars of different capacities.

## How It Works

The solution is implemented using a Breadth-First Search (BFS) algorithm, which systematically explores all possible states until it finds the goal state. Here's a brief overview of the algorithm:

1. **Initialization**: Start with the initial state, where both jugs are empty.

2. **Queue**: Use a queue to keep track of states to be explored.

3. **Exploration**:
   - While the queue is not empty:
     - Dequeue a state.
     - Check if the current state is the goal state (desired amount of water in one of the jugs).
     - If yes, return the solution.
     - If no, generate next possible states based on allowed actions (fill, empty, transfer).
     - Enqueue unvisited states.

4. **Visited States**: Maintain a record of visited states to avoid revisiting the same state.

5. **Solution Format**: The solution is formatted to provide a sequence of actions, along with the state of the jugs after each action.

## Additional Resources

- [Breadth-First Search (BFS)](https://en.wikipedia.org/wiki/Breadth-first_search): Learn more about BFS.
- [Queue (Abstract Data Type)](https://en.wikipedia.org/wiki/Queue_(abstract_data_type)): Explore the concept of queues.

## Requirements

- [Docker](https://www.docker.com/products/docker-desktop/)

## Usage

1. Clone this repository:

   ```bash
   git clone https://github.com/AleVinokur/Water-Jug-Challenge.git


2. Navigate to the project directory:
    ```bash
    cd Water-Jug-Challenge
   
3. Build and run the Docker container:
    ```bash
    docker-compose up --build


# API
The API exposes a single endpoint to solve the Water Jug Challenge.

## Endpoint
### URL:
 http://localhost:8080/api/water-jug-challenge

### Method:
 POST

## Input Parameters
The request must include a JSON body with the following parameters:

bucket_x (integer): Size of the first bucket.
bucket_y (integer): Size of the second bucket.
measure_z (integer): Desired target.

## Example Input: 

{
  "bucket_x": 10,
  "bucket_y": 2,
  "measure_z": 4
}


## Swagger Documentation

Explore and test the API directly from the Swagger interface available at http://localhost:8080/api/documentation.

## Test API in Postman

- [Download Postman](https://www.postman.com/downloads/)
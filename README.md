The system supports creating, updating, and deleting tasks, assigning roles to users, tracking task statuses, and managing user contributions to projects. It's a multi-user system with role-based access control.

## Features
- User Authentication: Allows users to log in and manage their tasks.
- Task Management: Users can create, update, delete, and filter tasks based on priority and status.
- Project Management: Projects can have multiple users with specific roles and contribution hours.
- Filtering: Users can filter tasks based on their priority or status.
- Assigning Tasks to Projects: Tasks are associated with specific projects, and users can manage their tasks per project.

## Installation
- git clone https:https://github.com/FaezaAldarweesh/task_6/tree/master
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- php artisan db:seed
- php artisan serve

## API Endpoints
The system provides a set of RESTful APIs to manage tasks and projects.

**Task Endpoints**
- GET /tasks: Get a list of all tasks.
- POST /tasks: Create a new task.
- PUT /tasks/{id}: Update an existing task.
- DELETE /tasks/{id}: Delete a task.
- GET /tasks/filter: Filter tasks based on priority and status.

**Project Endpoints**
- GET /projects: Get a list of all projects.
- POST /projects: Create a new project.
- PUT /projects/{id}: Update an existing project.
- DELETE /projects/{id}: Delete a project.

**User Management**
- GET /users: Get a list of all users.
- POST /users: Create a new user.
- PUT /users/{id}: Update an existing user.
- DELETE /users/{id}: Delete a user.

  ## postman
  - documentation link : https://documenter.getpostman.com/view/34467473/2sAXqqcNpe

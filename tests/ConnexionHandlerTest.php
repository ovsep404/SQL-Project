<?php

require_once __DIR__ . '/../requests/ConnexionHandler.php';

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

// Example Mock library

class ConnexionHandlerTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @dataProvider userProvider
     */

    public function testValidLogin($email, $password, $expectedUserId, $expectedUserType, $expectedRedirect): void
    {
        // Create PDO mock
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn([
            'abonne_id' => $expectedUserId,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'type_utilisateur' => $expectedUserType,
        ]);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($stmtMock);

        // Instantiate Connexion Handler with mock
        $handler = new ConnexionHandler($pdoMock);

        // Test login
        $result = $handler->handleLogin($email, $password);

        // Assert returned values
        $this->assertSame($expectedUserId, $result['user_id']);
        $this->assertSame($expectedUserType, $result['user_type']);
        $this->assertSame($expectedRedirect, $result['redirect']);
    }

    public static function userProvider(): array
    {
        return [
            ['test@example.com', 'validPassword', 1, 'abonne', '../view/livre.php'],
            ['admin@example.com', 'adminPassword', 2, 'gestionnaire', '../view/abonne.php'],
            ['gestionnaire@example.com', 'gestionnairePassword', 3, 'gestionnaire', '../view/abonne.php'],
        ];
    }

    public function testInvalidPassword(): void
    {
        // Create PDO mock
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn([
            'abonne_id' => 1,
            'email' => 'test@example.com',
            'password' => password_hash('validPassword', PASSWORD_DEFAULT),
            'type_utilisateur' => 'abonne',
        ]);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($stmtMock);

        // Instantiate Connexion Handler with mock
        $handler = new ConnexionHandler($pdoMock);

        // Simulate POST request with incorrect password
        $email = 'test@example.com';
        $password = 'invalidPassword';

        // Test login
        $result = $handler->handleLogin($email, $password);

        // Assert returned values
        $this->assertSame('../view/connexion.php?message=Invalid email or password', $result['redirect']);
    }

    public function testInvalidLogin(): void
    {
        // Create PDO mock
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn(false);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->method('prepare')->willReturn($stmtMock);

        // Instantiate Connexion Handler with mock
        $handler = new ConnexionHandler($pdoMock);

        // Test login with nonexistent user
        $result = $handler->handleLogin('nonexistent@example.com', 'somePassword');
        $this->assertSame('../view/connexion.php?message=Invalid email or password', $result['redirect']);

        // Test login with empty email
        $result = $handler->handleLogin('', 'somePassword');
        $this->assertSame('../view/connexion.php?message=Invalid email or password', $result['redirect']);

        // Test login with empty password
        $result = $handler->handleLogin('test@example.com', '');
        $this->assertSame('../view/connexion.php?message=Invalid email or password', $result['redirect']);
    }

}


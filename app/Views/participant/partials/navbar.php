<?php

use App\Core\Auth;
use App\Core\Csrf;

$user = Auth::user();

$csrfToken = Csrf::token();

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">

    <div class="container">

        <!-- BRAND -->

        <a href="/TECHATHON/public/participant" class="navbar-brand fw-bold">
            TECHATHON
        </a>


        <!-- MOBILE TOGGLER -->

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#participantNavbar"
            aria-controls="participantNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <!-- NAVIGATION -->

        <div class="collapse navbar-collapse" id="participantNavbar">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">


                <!-- DASHBOARD -->

                <li class="nav-item">

                    <a class="nav-link" href="/TECHATHON/public/">
                        Dashboard
                    </a>

                </li>


                <!-- HACKATHONS -->

                <li class="nav-item">

                    <a class="nav-link" href="/TECHATHON/public/participant/hackathons">
                        Hackathons
                    </a>

                </li>


                <!-- MY TEAMS -->

                <li class="nav-item">

                    <a class="nav-link" href="/TECHATHON/public/participant/teams">
                        My Teams
                    </a>

                </li>


                <!-- MY REGISTRATIONS -->

                <li class="nav-item">

                    <a class="nav-link" href="/TECHATHON/public/participant/registrations">
                        My Registrations
                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="/TECHATHON/public/participant/projects">
                        My Projects
                    </a>

                </li>


                <!-- INVITATIONS -->

                <li class="nav-item">

                    <a class="nav-link" href="/TECHATHON/public/participant/invitations">
                        Invitations
                    </a>

                </li>

            </ul>


            <!-- USER AREA -->

            <div class="d-flex
                flex-column
                flex-lg-row
                align-items-lg-center
                gap-2">


                <!-- LOGGED-IN USER -->

                <?php if ($user): ?>

                    <span class="text-white-50 small">

                        <?= htmlspecialchars(
                            $user['name'] ?? 'Participant'
                        ) ?>

                    </span>

                <?php endif; ?>


                <!-- LOGOUT -->

                <form method="POST" action="/TECHATHON/public/logout" class="m-0">

                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars(
                        $csrfToken
                    ) ?>">


                    <button type="submit" class="btn btn-outline-light btn-sm">
                        Logout
                    </button>

                </form>


            </div>

        </div>

    </div>

</nav>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($title) ?> - TECHATHON
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<!-- ============================================================
     NAVIGATION
============================================================ -->

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            href="/TECHATHON/public/admin"
            class="navbar-brand fw-bold"
        >
            TECHATHON Admin
        </a>


        <div class="d-flex align-items-center gap-3">

            <span class="text-white">

                <?= htmlspecialchars(
                    $user['name'] ?? 'Administrator'
                ) ?>

            </span>


            <form
                method="POST"
                action="/TECHATHON/public/logout"
                class="m-0"
            >

                <button
                    type="submit"
                    class="btn btn-outline-light btn-sm"
                >
                    Logout
                </button>

            </form>

        </div>

    </div>

</nav>



<!-- ============================================================
     MAIN CONTENT
============================================================ -->

<main class="container py-5">


    <!-- ========================================================
         PAGE HEADER
    ========================================================= -->

    <div class="mb-4">

        <h1 class="fw-bold">
            Admin Dashboard
        </h1>

        <p class="text-muted mb-0">
            Manage the TECHATHON hackathon management platform.
        </p>

    </div>



    <!-- ========================================================
         STATISTICS
    ========================================================= -->

    <div class="row g-4">


        <!-- Total Users -->

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Users
                    </h6>

                    <h2 class="fw-bold mb-0">
                        <?= (int) $statistics['total_users'] ?>
                    </h2>

                </div>

            </div>

        </div>



        <!-- Participants -->

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Participants
                    </h6>

                    <h2 class="fw-bold mb-0">
                        <?= (int) $statistics['participants'] ?>
                    </h2>

                </div>

            </div>

        </div>



        <!-- Organizers -->

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Organizers
                    </h6>

                    <h2 class="fw-bold mb-0">
                        <?= (int) $statistics['organizers'] ?>
                    </h2>

                </div>

            </div>

        </div>



        <!-- Judges -->

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Judges
                    </h6>

                    <h2 class="fw-bold mb-0">
                        <?= (int) $statistics['judges'] ?>
                    </h2>

                </div>

            </div>

        </div>



        <!-- Total Hackathons -->

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Hackathons
                    </h6>

                    <h2 class="fw-bold mb-0">
                        <?= (int) $statistics['total_hackathons'] ?>
                    </h2>

                </div>

            </div>

        </div>



        <!-- Pending Approvals -->

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Pending Approvals
                    </h6>

                    <h2 class="fw-bold mb-0">
                        <?= (int) $statistics['pending_hackathons'] ?>
                    </h2>

                </div>

            </div>

        </div>

    </div>



    <!-- ========================================================
         ADMINISTRATION
    ========================================================= -->

    <div class="card shadow-sm mt-5">

        <div class="card-body">

            <h5 class="card-title fw-bold">
                Administration
            </h5>

            <p class="text-muted">
                Manage users, hackathons and platform resources.
            </p>


            <div class="d-flex flex-wrap gap-2">


                <!-- User Management -->

                <a
                    href="/TECHATHON/public/admin/users"
                    class="btn btn-primary"
                >
                    Manage Users
                </a>



                <!-- Hackathon Management -->

                <a
                    href="/TECHATHON/public/admin/hackathons"
                    class="btn btn-success"
                >
                    Manage Hackathons
                </a>



                <!-- Pending Approvals -->

                <a
                    href="/TECHATHON/public/admin/hackathons/pending"
                    class="btn btn-warning"
                >
                    Pending Approvals
                </a>

            </div>

        </div>

    </div>



    <!-- ========================================================
         QUICK OVERVIEW
    ========================================================= -->

    <div class="row g-4 mt-1">


        <!-- Platform Management -->

        <div class="col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Platform Management
                    </h5>

                    <p class="text-muted">
                        Control users and hackathons from the
                        administration panel.
                    </p>


                    <ul class="mb-0">

                        <li class="mb-2">
                            Manage registered users
                        </li>

                        <li class="mb-2">
                            Review hackathons
                        </li>

                        <li class="mb-2">
                            Approve or reject hackathons
                        </li>

                        <li>
                            Monitor platform activity
                        </li>

                    </ul>

                </div>

            </div>

        </div>



        <!-- Hackathon Workflow -->

        <div class="col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Hackathon Workflow
                    </h5>

                    <p class="text-muted">
                        Hackathons follow the approval workflow
                        controlled by the administrator.
                    </p>


                    <div class="d-flex flex-wrap gap-2">

                        <span class="badge text-bg-secondary">
                            Draft
                        </span>

                        <span class="text-muted">
                            →
                        </span>

                        <span class="badge text-bg-warning">
                            Pending Approval
                        </span>

                        <span class="text-muted">
                            →
                        </span>

                        <span class="badge text-bg-success">
                            Approved
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


</main>



<!-- ============================================================
     BOOTSTRAP JAVASCRIPT
============================================================ -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
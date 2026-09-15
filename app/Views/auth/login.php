<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= htmlspecialchars($title) ?> - TECHATHON</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <h1 class="h3">TECHATHON</h1>

                        <p class="text-muted mb-0">
                            Login to your account
                        </p>
                    </div>

                    <?php if ($error = \App\Core\Session::getFlash('error')): ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>

                    <?php endif; ?>

                    <?php if ($success = \App\Core\Session::getFlash('success')): ?>

                        <div class="alert alert-success">
                            <?= htmlspecialchars($success) ?>
                        </div>

                    <?php endif; ?>

                    <form
                        method="POST"
                        action="/TECHATHON/public/login"
                    >

                        <div class="mb-3">

                            <label
                                for="email"
                                class="form-label"
                            >
                                Email Address
                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                required
                                autocomplete="email"
                            >

                        </div>

                        <div class="mb-4">

                            <label
                                for="password"
                                class="form-label"
                            >
                                Password
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"
                            >

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Login
                        </button>

                    </form>

                    <div class="text-center mt-4">

                        <span class="text-muted">
                            Don't have an account?
                        </span>

                        <a href="/TECHATHON/public/register">
                            Create Account
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
<?php
require_once 'config.php';

// Additional functions implementation
function getAllTools($pdo) {
    $stmt = $pdo->query("SELECT * FROM tools ORDER BY position ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addTool($pdo, $name, $icon, $position) {
    $stmt = $pdo->prepare("INSERT INTO tools (name, icon, position) VALUES (?, ?, ?)");
    return $stmt->execute([$name, $icon, $position]);
}

function updateTool($pdo, $id, $name, $icon, $position) {
    $stmt = $pdo->prepare("UPDATE tools SET name=?, icon=?, position=? WHERE id=?");
    return $stmt->execute([$name, $icon, $position, $id]);
}

function deleteTool($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM tools WHERE id=?");
    return $stmt->execute([$id]);
}

function getAllServices($pdo) {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY position ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateService($pdo, $id, $title, $description, $icon) {
    $stmt = $pdo->prepare("UPDATE services SET title=?, description=?, icon=? WHERE id=?");
    return $stmt->execute([$title, $description, $icon, $id]);
}

function getAllProjects($pdo) {
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY position ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateProject($pdo, $id, $title, $description, $icon) {
    $stmt = $pdo->prepare("UPDATE projects SET title=?, description=?, icon=? WHERE id=?");
    return $stmt->execute([$title, $description, $icon, $id]);
}

function getSocialLinks($pdo) {
    $stmt = $pdo->query("SELECT * FROM social_links");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateSocialLink($pdo, $id, $platform, $url) {    $stmt = $pdo->prepare("UPDATE social_links SET platform=?, url=? WHERE id=?");
    return $stmt->execute([$platform, $url, $id]);
}

// Initialize database tables if they don't exist
function initializeDatabase() {
    $pdo = connectDB();
    
    // Create tools table
    $pdo->exec("CREATE TABLE IF NOT EXISTS tools (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        icon VARCHAR(255),
        position INT DEFAULT 0
    )");
    
    // Create services table
    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        icon VARCHAR(255),
        position INT DEFAULT 0
    )");
    
    // Create projects table
    $pdo->exec("CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        icon VARCHAR(255),
        position INT DEFAULT 0
    )");
    
    // Create social_links table
    $pdo->exec("CREATE TABLE IF NOT EXISTS social_links (
        id INT AUTO_INCREMENT PRIMARY KEY,
        platform VARCHAR(100) NOT NULL,
        url VARCHAR(500) NOT NULL
    )");
    
    // Insert default tools if empty
    $count = $pdo->query("SELECT COUNT(*) FROM tools")->fetchColumn();
    if ($count == 0) {
        $defaultTools = [
            ['Python', 'fab fa-python', 1],
            ['JavaScript', 'fab fa-js', 2],
            ['React', 'fab fa-react', 3],
            ['Docker', 'fab fa-docker', 4],
            ['Kubernetes', 'fas fa-server', 5],            ['AWS/Azure', 'fas fa-cloud', 6],
            ['TensorFlow', 'fas fa-bolt', 7],
            ['OpenAI', 'fas fa-robot', 8]
        ];
        
        foreach ($defaultTools as $tool) {
            $stmt = $pdo->prepare("INSERT INTO tools (name, icon, position) VALUES (?, ?, ?)");
            $stmt->execute($tool);
        }
    }
    
    // Insert default services if empty
    $count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    if ($count == 0) {
        $defaultServices = [
            ['AI-Powered Solutions', 'Leveraging artificial intelligence and machine learning to create intelligent software that adapts and learns from data.', 'fas fa-brain'],
            ['Process Automation', 'Streamlining workflows and automating repetitive tasks to increase efficiency and reduce operational overhead.', 'fas fa-cogs'],
            ['Custom Software', 'Tailor-made applications built specifically for your business needs, designed for scalability and performance.', 'fas fa-code']
        ];
        
        foreach ($defaultServices as $index => $service) {
            $stmt = $pdo->prepare("INSERT INTO services (title, description, icon, position) VALUES (?, ?, ?, ?)");
            $stmt->execute([$service[0], $service[1], $service[2], $index]);
        }
    }
    
    // Insert default projects if empty
    $count = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    if ($count == 0) {
        $defaultProjects = [
            ['IntelliBot Platform', 'AI-powered customer service automation system with natural language processing capabilities.', 'fas fa-robot'],
            ['DataFlow Analytics', 'Real-time data processing pipeline for large-scale analytics and visualization.', 'fas fa-chart-line'],
            ['SecureVault', 'Enterprise-grade security solution for data encryption and access management.', 'fas fa-shield-alt']
        ];
        
        foreach ($defaultProjects as $index => $project) {
            $stmt = $pdo->prepare("INSERT INTO projects (title, description, icon, position) VALUES (?, ?, ?, ?)");
            $stmt->execute([$project[0], $project[1], $project[2], $index]);
        }
    }
    
    // Insert default social links if empty
    $count = $pdo->query("SELECT COUNT(*) FROM social_links")->fetchColumn();
    if ($count == 0) {
        $defaultLinks = [
            ['GitLab', 'https://gitlab.com/denzie-studio'],
            ['GitHub', 'https://github.com/denzie-studio'],
            ['LinkedIn', 'https://linkedin.com/company/denzie-studio']
        ];
                foreach ($defaultLinks as $link) {
            $stmt = $pdo->prepare("INSERT INTO social_links (platform, url) VALUES (?, ?)");
            $stmt->execute($link);
        }
    }
}

initializeDatabase();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DENZIE STUDIO Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #0a0a12;
            --secondary-dark: #1a1a2e;
            --accent-blue: #00f3ff;
            --accent-purple: #b967db;
            --glass-bg: rgba(25, 25, 45, 0.2);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-primary: #ffffff;
            --text-secondary: #b0b0c0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
            color: var(--text-primary);
            min-height: 100vh;
            padding-top: 60px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Admin Header */        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            padding: 15px 20px;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-logo {
            font-size: 1.5rem;
            font-weight: bold;
            background: linear-gradient(45deg, var(--accent-blue), var(--accent-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .admin-nav {
            display: flex;
            gap: 20px;
        }

        .admin-nav a {
            color: var(--text-primary);
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .admin-nav a:hover, .admin-nav a.active {
            background: rgba(0, 243, 255, 0.1);
        }

        .logout-btn {
            background: linear-gradient(45deg, var(--accent-blue), var(--accent-purple));
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            padding: 20px;
            backdrop-filter: blur(10px);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-icon {
            font-size: 1.8rem;
            margin-right: 10px;
            color: var(--accent-blue);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-blue);
        }

        /* Forms */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-secondary);
        }
        .form-control {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            border-radius: 5px;
            color: var(--text-primary);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--accent-blue), var(--accent-purple));
            color: white;
        }

        .btn-danger {
            background: #ff4757;
            color: white;
        }

        .btn-success {
            background: #2ed573;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* Tables */
        .table-container {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
        }

        table {            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--glass-border);
        }

        th {
            background: rgba(0, 0, 0, 0.2);
            color: var(--accent-blue);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        /* Login Form */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 60px);
        }

        .login-form {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }

        .login-title {
            text-align: center;
            margin-bottom: 20px;
            color: var(--accent-blue);
        }

        /* Page Sections */
        .page-section {            display: none;
        }

        .page-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.8rem;
            background: linear-gradient(45deg, var(--accent-blue), var(--accent-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-nav {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php if (!isLoggedIn()): ?>
        <div class="login-container">
            <div class="login-form">
                <h2 class="login-title">Admin Login</h2>
                <form method="post" action="?page=login">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="admin-header">
            <div class="admin-logo">DENZIE STUDIO ADMIN</div>
            <div class="admin-nav">
                <a href="?page=dashboard" class="<?php echo ($_GET['page'] ?? 'dashboard') === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
                <a href="?page=tools" class="<?php echo ($_GET['page'] ?? '') === 'tools' ? 'active' : ''; ?>">Tools</a>
                <a href="?page=services" class="<?php echo ($_GET['page'] ?? '') === 'services' ? 'active' : ''; ?>">Services</a>
                <a href="?page=projects" class="<?php echo ($_GET['page'] ?? '') === 'projects' ? 'active' : ''; ?>">Projects</a>
                <a href="?page=social" class="<?php echo ($_GET['page'] ?? '') === 'social' ? 'active' : ''; ?>">Social Links</a>
                <button class="logout-btn" onclick="location.href='?action=logout'">Logout</button>
            </div>
        </div>

        <div class="container">
            <!-- Dashboard Page -->
            <div id="page-dashboard" class="page-section <?php echo ($_GET['page'] ?? 'dashboard') === 'dashboard' ? 'active' : ''; ?>">
                <h2 class="section-title">Dashboard</h2>
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-tools"></i></div>
                            <div class="card-title">Tools</div>
                        </div>
                        <div class="card-value"><?php echo count(getAllTools(connectDB())); ?></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-concierge-bell"></i></div>
                            <div class="card-title">Services</div>
                        </div>
                        <div class="card-value"><?php echo count(getAllServices(connectDB())); ?></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon"><i class="fas fa-project-diagram"></i></div>
                            <div class="card-title">Projects</div>
                        </div>
                        <div class="card-value"><?php echo count(getAllProjects(connectDB())); ?></div>
                    </div>
                    <div class="card">
                        <div class="card-header">                            <div class="card-icon"><i class="fas fa-share-alt"></i></div>
                            <div class="card-title">Social Links</div>
                        </div>
                        <div class="card-value"><?php echo count(getSocialLinks(connectDB())); ?></div>
                    </div>
                </div>
                
                <div class="card">
                    <h3>Quick Actions</h3>
                    <p>Manage your website content from here:</p>
                    <ul>
                        <li><a href="?page=tools">Manage Tools & Technologies</a></li>
                        <li><a href="?page=services">Edit Services</a></li>
                        <li><a href="?page=projects">Update Projects</a></li>
                        <li><a href="?page=social">Configure Social Links</a></li>
                    </ul>
                </div>
            </div>

            <!-- Tools Management Page -->
            <div id="page-tools" class="page-section <?php echo ($_GET['page'] ?? '') === 'tools' ? 'active' : ''; ?>">
                <div class="section-header">
                    <h2 class="section-title">Manage Tools</h2>
                    <button class="btn btn-primary" onclick="toggleForm('add-tool')">Add Tool</button>
                </div>
                
                <div id="add-tool" class="card" style="display:none;">
                    <h3>Add New Tool</h3>
                    <form method="post" action="?page=tools&action=add">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Icon Class (Font Awesome)</label>
                            <input type="text" name="icon" class="form-control" value="fas fa-code" placeholder="e.g., fas fa-python">
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <input type="number" name="position" class="form-control" value="0" min="0">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Tool</button>
                        <button type="button" class="btn" onclick="toggleForm('add-tool')" style="background: #ff4757; color: white;">Cancel</button>
                    </form>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>                                <th>Icon</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $tools = getAllTools(connectDB());
                            foreach ($tools as $tool): ?>
                            <tr>
                                <td><i class="<?php echo htmlspecialchars($tool['icon']); ?>"></i></td>
                                <td><?php echo htmlspecialchars($tool['name']); ?></td>
                                <td><?php echo $tool['position']; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-primary" onclick="editTool(<?php echo $tool['id']; ?>)">Edit</button>
                                        <form method="post" action="?page=tools&action=delete" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="id" value="<?php echo $tool['id']; ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div id="edit-tool" class="card" style="display:none;">
                    <h3>Edit Tool</h3>
                    <form id="edit-tool-form" method="post" action="?page=tools&action=update">
                        <input type="hidden" name="id" id="edit-tool-id">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="edit-tool-name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Icon Class (Font Awesome)</label>
                            <input type="text" name="icon" id="edit-tool-icon" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <input type="number" name="position" id="edit-tool-position" class="form-control" min="0">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Tool</button>
                        <button type="button" class="btn" onclick="toggleForm('edit-tool')" style="background: #ff4757; color: white;">Cancel</button>
                    </form>
                </div>
            </div>
            <!-- Services Management Page -->
            <div id="page-services" class="page-section <?php echo ($_GET['page'] ?? '') === 'services' ? 'active' : ''; ?>">
                <div class="section-header">
                    <h2 class="section-title">Manage Services</h2>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Icon</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $services = getAllServices(connectDB());
                            foreach ($services as $service): ?>
                            <tr>
                                <td><i class="<?php echo htmlspecialchars($service['icon']); ?>"></i></td>
                                <td><?php echo htmlspecialchars($service['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($service['description'], 0, 50)); ?>...</td>
                                <td><?php echo $service['position']; ?></td>
                                <td>
                                    <button class="btn btn-primary" onclick="editService(<?php echo $service['id']; ?>)">Edit</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div id="edit-service" class="card" style="display:none;">
                    <h3>Edit Service</h3>
                    <form id="edit-service-form" method="post" action="?page=services&action=update">
                        <input type="hidden" name="id" id="edit-service-id">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" id="edit-service-title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="edit-service-description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Icon Class (Font Awesome)</label>                            <input type="text" name="icon" id="edit-service-icon" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <input type="number" name="position" id="edit-service-position" class="form-control" min="0">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Service</button>
                        <button type="button" class="btn" onclick="toggleForm('edit-service')" style="background: #ff4757; color: white;">Cancel</button>
                    </form>
                </div>
            </div>

            <!-- Projects Management Page -->
            <div id="page-projects" class="page-section <?php echo ($_GET['page'] ?? '') === 'projects' ? 'active' : ''; ?>">
                <div class="section-header">
                    <h2 class="section-title">Manage Projects</h2>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Icon</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $projects = getAllProjects(connectDB());
                            foreach ($projects as $project): ?>
                            <tr>
                                <td><i class="<?php echo htmlspecialchars($project['icon']); ?>"></i></td>
                                <td><?php echo htmlspecialchars($project['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($project['description'], 0, 50)); ?>...</td>
                                <td><?php echo $project['position']; ?></td>
                                <td>
                                    <button class="btn btn-primary" onclick="editProject(<?php echo $project['id']; ?>)">Edit</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div id="edit-project" class="card" style="display:none;">
                    <h3>Edit Project</h3>
                    <form id="edit-project-form" method="post" action="?page=projects&action=update">                        <input type="hidden" name="id" id="edit-project-id">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" id="edit-project-title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="edit-project-description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Icon Class (Font Awesome)</label>
                            <input type="text" name="icon" id="edit-project-icon" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <input type="number" name="position" id="edit-project-position" class="form-control" min="0">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Project</button>
                        <button type="button" class="btn" onclick="toggleForm('edit-project')" style="background: #ff4757; color: white;">Cancel</button>
                    </form>
                </div>
            </div>

            <!-- Social Links Management Page -->
            <div id="page-social" class="page-section <?php echo ($_GET['page'] ?? '') === 'social' ? 'active' : ''; ?>">
                <div class="section-header">
                    <h2 class="section-title">Manage Social Links</h2>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Platform</th>
                                <th>URL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $links = getSocialLinks(connectDB());
                            foreach ($links as $link): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($link['platform']); ?></td>
                                <td><a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank"><?php echo htmlspecialchars($link['url']); ?></a></td>
                                <td>
                                    <button class="btn btn-primary" onclick="editSocialLink(<?php echo $link['id']; ?>)">Edit</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>                        </tbody>
                    </table>
                </div>
                
                <div id="edit-social" class="card" style="display:none;">
                    <h3>Edit Social Link</h3>
                    <form id="edit-social-form" method="post" action="?page=social&action=update">
                        <input type="hidden" name="id" id="edit-social-id">
                        <div class="form-group">
                            <label>Platform</label>
                            <input type="text" name="platform" id="edit-social-platform" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>URL</label>
                            <input type="url" name="url" id="edit-social-url" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Link</button>
                        <button type="button" class="btn" onclick="toggleForm('edit-social')" style="background: #ff4757; color: white;">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // Toggle form visibility
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        // Edit functions
        function editTool(id) {
            // This would normally fetch data via AJAX
            // For demo purposes, we'll just show the edit form
            document.getElementById('edit-tool-id').value = id;
            document.getElementById('edit-tool-name').value = 'Sample Tool';
            document.getElementById('edit-tool-icon').value = 'fas fa-code';
            document.getElementById('edit-tool-position').value = 0;
            toggleForm('edit-tool');
        }

        function editService(id) {
            document.getElementById('edit-service-id').value = id;
            document.getElementById('edit-service-title').value = 'Sample Service';
            document.getElementById('edit-service-description').value = 'Sample description for this service.';
            document.getElementById('edit-service-icon').value = 'fas fa-cogs';
            document.getElementById('edit-service-position').value = 0;
            toggleForm('edit-service');
        }
        function editProject(id) {
            document.getElementById('edit-project-id').value = id;
            document.getElementById('edit-project-title').value = 'Sample Project';
            document.getElementById('edit-project-description').value = 'Sample description for this project.';
            document.getElementById('edit-project-icon').value = 'fas fa-project-diagram';
            document.getElementById('edit-project-position').value = 0;
            toggleForm('edit-project');
        }

        function editSocialLink(id) {
            document.getElementById('edit-social-id').value = id;
            document.getElementById('edit-social-platform').value = 'Sample Platform';
            document.getElementById('edit-social-url').value = 'https://example.com';
            toggleForm('edit-social');
        }

        // Handle form submissions
        document.addEventListener('DOMContentLoaded', function() {
            // Handle login form if exists
            const loginForm = document.querySelector('.login-form form');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Form validation would happen here
                });
            }

            // Show active page
            const currentPage = window.location.search.split('=')[1] || 'dashboard';
            document.getElementById(`page-${currentPage}`).classList.add('active');
        });
    </script>

    <?php
    // Handle form submissions
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if (username'], $_POST['password'])) {
            header("Location: ?page=dashboard");
            exit;
        } else {
            echo "<script>alert('Invalid credentials');</script>";
        }
    }

    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        logout();
    }

    if (isLoggedIn()) {
        $pdo = connectDB();        
        // Handle Tools actions
        if ($_GET['page'] === 'tools') {
            if (isset($_GET['action']) && $_GET['action'] === 'add') {
                addTool($pdo, $_POST['name'], $_POST['icon'], $_POST['position']);
                header("Location: ?page=tools");
                exit;
            }
            
            if (isset($_GET['action']) && $_GET['action'] === 'update') {
                updateTool($pdo, $_POST['id'], $_POST['name'], $_POST['icon'], $_POST['position']);
                header("Location: ?page=tools");
                exit;
            }
            
            if (isset($_GET['action']) && $_GET['action'] === 'delete') {
                deleteTool($pdo, $_POST['id']);
                header("Location: ?page=tools");
                exit;
            }
        }
        
        // Handle Services actions
        if ($_GET['page'] === 'services' && isset($_GET['action']) && $_GET['action'] === 'update') {
            updateService($pdo, $_POST['id'], $_POST['title'], $_POST['description'], $_POST['icon']);
            header("Location: ?page=services");
            exit;
        }
        
        // Handle Projects actions
        if ($_GET['page'] === 'projects' && isset($_GET['action']) && $_GET['action'] === 'update') {
            updateProject($pdo, $_POST['id'], $_POST['title'], $_POST['description'], $_POST['icon']);
            header("Location: ?page=projects");
            exit;
        }
        
        // Handle Social Links actions
        if ($_GET['page'] === 'social' && isset($_GET['action']) && $_GET['action'] === 'update') {
            updateSocialLink($pdo, $_POST['id'], $_POST['platform'], $_POST['url']);
            header("Location: ?page=social");
            exit;
        }
    }
    ?>
</body>
</html>

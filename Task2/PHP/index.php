<?php
echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DevOps Tools Map</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
            display: flex;
        }

        .sidebar {
            width: 200px;
            padding: 20px;
            background-color: #e6f0f8;
            color: #333;
            height: 100vh;
            box-sizing: border-box;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }

        .left {
            border-right: 1px solid #ccc;
        }

        .right {
            border-left: 1px solid #ccc;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h1 {
            font-size: 2.2rem;
            margin-top: 30px;
            color: #2c3e50;
        }

        h2 {
            font-weight: normal;
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 30px;
        }

        .map-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        ul {
            padding-left: 18px;
        }

        li {
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        a {
            color: #336699;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="sidebar left">
        <h3>💡 DevOps Tips</h3>
        <ul>
            <li>Automate everything</li>
            <li>Use CI/CD pipelines</li>
            <li>Monitor continuously</li>
            <li>Test early and often</li>
            <li>Fail fast, recover faster</li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Welcome to the DevOps Loop</h1>
        <h2>Explore the tools powering modern development & operations</h2>
        <div class="map-container">
            <img src="images/DevOps.jpg" usemap="#image-map" alt="DevOps Map">
            <map name="image-map">
                <area target="_blank" alt="Docker" title="Docker" href="https://www.docker.com/" coords="663,39,812,180" shape="rect">
                <area target="_blank" alt="Chef" title="Chef" href="https://www.chef.io/" coords="1023,133,884,268" shape="rect">
                <area target="_blank" alt="Ansible" title="Ansible" href="https://www.ansible.com/" coords="1026,305,891,444" shape="rect">
                <area target="_blank" alt="Kubernetes" title="Kubernetes" href="https://kubernetes.io/" coords="886,613,1024,471" shape="rect">
                <area target="_blank" alt="Graylog" title="Graylog" href="https://www.graylog.org/" coords="879,556,738,695" shape="rect">
                <area target="_blank" alt="Grafana" title="Grafana" href="https://grafana.com/" coords="718,554,584,692" shape="rect">
                <area target="_blank" alt="Jenkins" title="Jenkins" href="https://www.jenkins.io/" coords="566,554,434,688" shape="rect">
                <area target="_blank" alt="OpenStack" title="OpenStack" href="https://www.openstack.org/" coords="418,555,289,683" shape="rect">
                <area target="_blank" alt="Azure" title="Azure" href="https://azure.microsoft.com/" coords="264,550,129,681" shape="rect">
                <area target="_blank" alt="Nexus" title="Nexus" href="https://www.sonatype.com/products/repository-oss" coords="211,379,75,514" shape="rect">
                <area target="_blank" alt="Gradle" title="Gradle" href="https://gradle.org/" coords="209,225,68,362" shape="rect">
                <area target="_blank" alt="AWS" title="AWS" href="https://aws.amazon.com/" coords="641,42,504,179" shape="rect">
                <area target="_blank" alt="Git" title="Git" href="https://git-scm.com/" coords="484,38,349,179" shape="rect">
                <area target="_blank" alt="GitLab" title="GitLab" href="https://gitlab.com/" coords="327,37,195,178" shape="rect">
            </map>
        </div>
    </div>

    <div class="sidebar right">
        <h3>📚 Related Topics</h3>
        <ul>
            <li><a href="https://www.hashicorp.com/">HashiCorp Tools</a></li>
            <li><a href="https://about.gitlab.com/devops-tools/">GitLab DevOps Tools</a></li>
            <li><a href="https://roadmap.sh/devops">DevOps Roadmap</a></li>
            <li><a href="https://www.atlassian.com/devops">Atlassian DevOps Guide</a></li>
        </ul>
    </div>
</body>
</html>
HTML;
?>

To deploy to svn from git you need to:

1. Clone the git repository to your computer with:

    <pre>
    git clone git@github.com:Placester/wordpress-plugin.git placester
    </pre>

2. Checkout the latest update of the svn repository with:

    <pre>
    svn co http://plugins.svn.wordpress.org/placester
    </pre>

1. Update the "deploy.sh" MSG and BRANCH with the correct values. BRANCH should be "trunk" when the git deployment should be made in the trunk and "tags/[version]" when version tagging is needed.
1. Change the current directory to the git repository:

    <pre>
    cd [location of the cloned git repository]
    </pre>

1. Run the deployment script:

    <pre>
    sudo ./deploy.sh
    </pre>

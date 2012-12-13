## Synchronize TYPO3 TER to github.com ##

This project tries to synchronize all extensions from the TYPO3 TER to github, including every version as tag.

### How to use ###

#### Update local extension list ###
`console.php extensionlist -u` to fetch the extension list

`console.php extensionlist -i` for some information

#### Push extensions to github ###
`console.php worker --parse` to parse the local extension list and put it into the queue.

**Tip**: You can use `console.php worker --parse fo,bar` to load only the given extensions ("fo" and "bar").

`console.php worker --tag` to get the jobs done from the queue.

`console.php worker --clearqueue` to clear the queue.


## Authors ##
- Philipp Bergsmann http://www.opendo.at
- Georg Ringer http://www.cyberhouse.at
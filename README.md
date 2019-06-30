# LS-DTools

This will be a repository with a bunch of tools for anything related to development at Lightspeed.

### Getting started
* * *
```
git clone https://github.com/ls-lucas-krupa/ls-dtools.git
cd ./ls-dtools
make build
```
Make build will bring your container up automatically.  
Visit http://localhost:7331 and you should be good to go

#### Tools
* * *
##### Import Generator
Simple CSV import file generator.
It will generate a simple import file that contains 50/50 single items and matrix items.
Each of those rows will contain unique details. The files are stored locally so you can see 
the list that is persistent to **your** environment. You will need to refresh the page
every time you generate a file to show it on the page.  
Since these files are stored locally, you might end up with a bunch of them.   
You can `make clean-files` to remove them.
 


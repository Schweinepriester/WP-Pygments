#!/usr/bin/python

import sys
from pygments import highlight
from pygments.formatters import HtmlFormatter


# If there isn't only 2 args something weird is going on
expecting = 2;
if ( len(sys.argv) != expecting + 1 ):
	exit(128)

# Get the code
language = (sys.argv[1]).lower()
filename = sys.argv[2] 
f = open(filename, 'rb')
code = f.read()
f.close()


# PHP
if language == 'php':
	from pygments.lexers import PhpLexer
	lexer = PhpLexer(startinline=True)

# GUESS
elif language == 'guess':
	from pygments.lexers import guess_lexer
	lexer = guess_lexer( code )

# GET BY NAME
else:
	from pygments.lexers import get_lexer_by_name
	lexer = get_lexer_by_name( language )
	

# OUTPUT
formatter = HtmlFormatter(linenos=False, nowrap=True)
highlighted = highlight(code, lexer, formatter)
print highlighted
import sys, traceback;

modulename = sys.argv[1];
name = sys.argv[1];
try:
    print("Módulo: ", modulename);
    module = __import__(modulename, globals(), locals(  ), [name])
except:
    print('Não existe modulo: ', modulename);
    exit(1);

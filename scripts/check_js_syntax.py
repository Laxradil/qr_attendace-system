from pathlib import Path
text = Path('resources/views/student/dashboard.blade.php').read_text(encoding='utf-8')
start = text.index('<script>')
end = text.index('</script>', start)
script = text[start+8:end]
print('script lines:', len(script.splitlines()))
print('curly open', script.count('{'), 'close', script.count('}'))
print('paren open', script.count('('), 'close', script.count(')'))
print('bracket open', script.count('['), 'close', script.count(']'))
print(script)

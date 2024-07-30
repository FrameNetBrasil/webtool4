<?php

namespace App\Http\Controllers\Lexeme;

use App\Http\Controllers\Controller;
use App\Repositories\Lexeme;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;

#[Middleware(name: 'auth')]
class LexemeController extends Controller
{
    public static function listForTreeByLemma(int $idLemma): array
    {
        $result = [];
        $lexeme = new Lexeme();
        $lexemes = $lexeme->listByFilter((object)['idLemma' => $idLemma])->asQuery()->getResult();
        foreach ($lexemes as $lexeme) {
            $node = [];
            $node['id'] = 'x' . $lexeme['idLexeme'];
            $node['idLexeme'] = $lexeme['idLexeme'];
            $node['idLexemeEntry'] = $lexeme['idLexemeEntry'];
            $node['type'] = 'lexeme';
            $node['head'] = $lexeme['headWord'] == 1 ? " [head]" : '';
            $node['break'] = $lexeme['breakBefore'] == 1 ? " [break]" : '';
            $node['order'] = $lexeme['lexemeOrder'];
            $node['name'] = $lexeme['name'] . " [{$lexeme['POS']}][{$node['order']}]{$node['head']}{$node['break']}";
            $node['state'] = 'closed';
            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-lexeme';
            $node['children'] = [];
            $result[] = $node;
        }
        return $result;
    }
}

<?php 

$router->get('frame/new', [
	'uses' => 'App\Http\Controllers\Frame\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('frame', [
	'uses' => 'App\Http\Controllers\Frame\ResourceController@store',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('frame/{idFrame}', [
	'uses' => 'App\Http\Controllers\Frame\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}', [
	'uses' => 'App\Http\Controllers\Frame\ResourceController@get',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/fes', [
	'uses' => 'App\Http\Controllers\Frame\FEController@fes',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/fes/formNew', [
	'uses' => 'App\Http\Controllers\Frame\FEController@formNewFE',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/fes/grid', [
	'uses' => 'App\Http\Controllers\Frame\FEController@gridFE',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/semanticTypes', [
	'uses' => 'App\Http\Controllers\Frame\SemanticTypeController@semanticTypes',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/feRelations', [
	'uses' => 'App\Http\Controllers\Frame\FEInternalRelationController@feRelations',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/feRelations/formNew/{error?}', [
	'uses' => 'App\Http\Controllers\Frame\FEInternalRelationController@formNewFERelations',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/feRelations/grid', [
	'uses' => 'App\Http\Controllers\Frame\FEInternalRelationController@gridFERelations',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/lus', [
	'uses' => 'App\Http\Controllers\Frame\LUController@lus',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/lus/formNew', [
	'uses' => 'App\Http\Controllers\Frame\LUController@formNewLU',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/lus/grid', [
	'uses' => 'App\Http\Controllers\Frame\LUController@gridLU',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame', [
	'uses' => 'App\Http\Controllers\Frame\BrowseController@browse',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('frame/grid', [
	'uses' => 'App\Http\Controllers\Frame\BrowseController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('report/frame/grid', [
	'uses' => 'App\Http\Controllers\Frame\ReportController@grid',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/frame/{idFrame?}/{lang?}', [
	'uses' => 'App\Http\Controllers\Frame\ReportController@report',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/list/forSelect', [
	'uses' => 'App\Http\Controllers\Frame\ReportController@listForSelect',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/listScenario/forSelect', [
	'uses' => 'App\Http\Controllers\Frame\ReportController@listScenarioForSelect',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/classification', [
	'uses' => 'App\Http\Controllers\Frame\ClassificationController@classification',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/classification/formFramalType', [
	'uses' => 'App\Http\Controllers\Frame\ClassificationController@formFramalType',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/classification/formFramalDomain', [
	'uses' => 'App\Http\Controllers\Frame\ClassificationController@formFramalDomain',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('frame/classification/domain', [
	'uses' => 'App\Http\Controllers\Frame\ClassificationController@framalDomain',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('frame/classification/type', [
	'uses' => 'App\Http\Controllers\Frame\ClassificationController@framalType',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/entries', [
	'uses' => 'App\Http\Controllers\Frame\EntryController@entries',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/relations', [
	'uses' => 'App\Http\Controllers\Frame\RelationController@relations',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/relations/formNew', [
	'uses' => 'App\Http\Controllers\Frame\RelationController@formNewRelation',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('frame/{id}/relations/grid', [
	'uses' => 'App\Http\Controllers\Frame\RelationController@gridRelation',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/c5', [
	'uses' => 'App\Http\Controllers\C5\ReportController@main',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/c5/data', [
	'uses' => 'App\Http\Controllers\C5\ReportController@data',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/c5/{idConcept}/{lang?}', [
	'uses' => 'App\Http\Controllers\C5\ReportController@report',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('report/c5/search', [
	'uses' => 'App\Http\Controllers\C5\ReportController@search',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('constraint/fe/{id}', [
	'uses' => 'App\Http\Controllers\Constraint\ConstraintController@constraintFE',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('constraint/fe/{idConstraintInstance}', [
	'uses' => 'App\Http\Controllers\Constraint\ConstraintController@deleteConstraintFE',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('constraint/lu/{id}', [
	'uses' => 'App\Http\Controllers\Constraint\ConstraintController@constraintLU',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('constraint/lu/{idConstraintInstance}', [
	'uses' => 'App\Http\Controllers\Constraint\ConstraintController@deleteConstraintLU',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('entry', [
	'uses' => 'App\Http\Controllers\Entry\EntryController@entry',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/grid', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{id}/subTypes', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@semanticTypes',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{id}/edit', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@get',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('semanticType/{idSemanticType}', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@masterDelete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/new', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@formNew',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('semanticType/new', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@new',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{idEntity}/childAdd/{root}', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@childFormAdd',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{idEntity}/childGrid', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@childGrid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('semanticType/{idEntity}/add', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@childAdd',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('semanticType/relation/{idEntityRelation}', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@childDelete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{idEntity}/childSubTypeAdd/{root}', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@childFormAddSubType',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('semanticType/{idEntity}/addSubType', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@childAddSubType',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{idEntity}/childSubTypeGrid', [
	'uses' => 'App\Http\Controllers\SemanticType\ResourceController@childGridSubType',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/semanticType', [
	'uses' => 'App\Http\Controllers\SemanticType\ReportController@main',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/semanticType/data', [
	'uses' => 'App\Http\Controllers\SemanticType\ReportController@data',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/semanticType/{idSemanticType?}/{lang?}', [
	'uses' => 'App\Http\Controllers\SemanticType\ReportController@report',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{id}/entries', [
	'uses' => 'App\Http\Controllers\SemanticType\EntryController@entries',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('document/{id}/edit', [
	'uses' => 'App\Http\Controllers\Document\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('document/{id}/formCorpus', [
	'uses' => 'App\Http\Controllers\Document\ResourceController@formCorpus',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('document', [
	'uses' => 'App\Http\Controllers\Document\ResourceController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('document/new', [
	'uses' => 'App\Http\Controllers\Document\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('document/new', [
	'uses' => 'App\Http\Controllers\Document\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('document/{id}', [
	'uses' => 'App\Http\Controllers\Document\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('document/listForSelect', [
	'uses' => 'App\Http\Controllers\Document\ResourceController@listForSelect',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('document/{id}/entries', [
	'uses' => 'App\Http\Controllers\Document\EntryController@entries',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('group/listForSelect', [
	'uses' => 'App\Http\Controllers\Group\ResourceController@listForSelect',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('group/new', [
	'uses' => 'App\Http\Controllers\Group\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('group/{id}/edit', [
	'uses' => 'App\Http\Controllers\Group\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('group/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\Group\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('group', [
	'uses' => 'App\Http\Controllers\Group\ResourceController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('group/new', [
	'uses' => 'App\Http\Controllers\Group\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('group/{id}', [
	'uses' => 'App\Http\Controllers\Group\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('/', [
	'uses' => 'App\Http\Controllers\AppController@main',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('changeLanguage/{language}', [
	'uses' => 'App\Http\Controllers\AppController@changeLanguage',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('app/search', [
	'uses' => 'App\Http\Controllers\AppController@appSearch',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('messages', [
	'uses' => 'App\Http\Controllers\AppController@messages',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('lu', [
	'uses' => 'App\Http\Controllers\LU\ResourceController@newLU',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lu/{id}/edit', [
	'uses' => 'App\Http\Controllers\LU\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lu/{id}/object', [
	'uses' => 'App\Http\Controllers\LU\ResourceController@object',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('lu/{id}', [
	'uses' => 'App\Http\Controllers\LU\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lu/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\LU\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('lu/{id}', [
	'uses' => 'App\Http\Controllers\LU\ResourceController@update',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lu/{id}/semanticTypes', [
	'uses' => 'App\Http\Controllers\LU\SemanticTypeController@semanticTypes',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lu/{id}/constraints', [
	'uses' => 'App\Http\Controllers\LU\ConstraintController@constraints',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lu/{id}/constraints/formNew/{fragment?}', [
	'uses' => 'App\Http\Controllers\LU\ConstraintController@constraintsFormNew',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lu/{id}/constraints/grid', [
	'uses' => 'App\Http\Controllers\LU\ConstraintController@constraintsGrid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('luCandidate', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@resource',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('luCandidate/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('luCandidate/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('luCandidate/new', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@new',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('luCandidate', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@newLU',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('luCandidate/{id}/edit', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@edit',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('luCandidate/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@formEdit',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('luCandidate/fes/{idFrame}', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@feCombobox',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('luCandidate/{id}', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@delete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('luCandidate', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@update',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('luCandidate/createLU', [
	'uses' => 'App\Http\Controllers\LU\LUCandidateController@createLU',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lu/list/forEvent', [
	'uses' => 'App\Http\Controllers\LU\BrowseController@listForEvent',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lu/list/forSelect', [
	'uses' => 'App\Http\Controllers\LU\BrowseController@listForSelect',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/lu/content/{idLU?}', [
	'uses' => 'App\Http\Controllers\LU\ReportController@reportContent',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/lu/{idLU?}', [
	'uses' => 'App\Http\Controllers\LU\ReportController@report',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('report/lu/grid', [
	'uses' => 'App\Http\Controllers\LU\ReportController@grid',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('report/lu/sentences', [
	'uses' => 'App\Http\Controllers\LU\ReportController@sentences',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('utils/importFullText', [
	'uses' => 'App\Http\Controllers\Utils\ImportFullTextController@resource',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('utils/importFullText/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Utils\ImportFullTextController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('utils/importFullText/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Utils\ImportFullTextController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('utils/importFullText/{id}/formImportFullText', [
	'uses' => 'App\Http\Controllers\Utils\ImportFullTextController@formEdit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('utils/importFullText', [
	'uses' => 'App\Http\Controllers\Utils\ImportFullTextController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('genericlabel', [
	'uses' => 'App\Http\Controllers\GenericLabel\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('genericlabel/grid', [
	'uses' => 'App\Http\Controllers\GenericLabel\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('genericlabel/data', [
	'uses' => 'App\Http\Controllers\GenericLabel\ResourceController@data',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('genericlabel/{id}/edit', [
	'uses' => 'App\Http\Controllers\GenericLabel\ResourceController@get',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('genericlabel/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\GenericLabel\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('genericlabel/{idGenericLabel}', [
	'uses' => 'App\Http\Controllers\GenericLabel\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('genericlabel/new', [
	'uses' => 'App\Http\Controllers\GenericLabel\ResourceController@formNew',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('genericlabel/new', [
	'uses' => 'App\Http\Controllers\GenericLabel\ResourceController@new',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('genericlabel', [
	'uses' => 'App\Http\Controllers\GenericLabel\ResourceController@update',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lexicon', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@browse',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lexicon/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('lexicon/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lexicon/lemma/new', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@formNewLemma',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lexicon/lemma/{idLemma}/lexemeentries', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@lexemeentries',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lexicon/lemma/{idLemma}/{fragment?}', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@lemma',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('lexicon/lemma/new', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@newLemma',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('lexicon/lemma/{idLemma}', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@updateLemma',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('lexicon/lemma/{idLemma}', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@deleteLemma',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lexicon/lexeme/new', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@formNewLexeme',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lexicon/lexeme/{idLexeme}/wordforms', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@wordforms',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('lexicon/lexeme/{idLexeme}/{fragment?}', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@lexeme',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('lexicon/lexeme/new', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@newLexeme',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('lexicon/lexeme/{idLexeme}', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@updateLexeme',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('lexicon/lexeme/{idLexeme}', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@deleteLexeme',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('lexicon/lexemeentry/new', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@newLexemeEntry',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('lexicon/lexemeentries/{idLexemeEntry}', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@deleteLexemeEntry',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('lexicon/wordform/new', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@newWordform',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('lexicon/wordform/{idWordForm}', [
	'uses' => 'App\Http\Controllers\Lexicon\ResourceController@deleteWordform',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('tqr2', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('tqr2/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('tqr2/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('tqr2/{id}/edit', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('tqr2/new', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('tqr2/new', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('tqr2/{id}', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('tqr2/argument/new', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@createArgument',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('tqr2/{id}/arguments', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@arguments',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('tqr2/arguments/{id}', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@deleteArgument',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('tqr2/qualialu/new', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@formNewQualiaLU',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('tqr2/list/forSelect', [
	'uses' => 'App\Http\Controllers\TQR2\ResourceController@listForSelect',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('corpus', [
	'uses' => 'App\Http\Controllers\Corpus\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('corpus/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Corpus\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('corpus/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Corpus\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('corpus/{id}/edit', [
	'uses' => 'App\Http\Controllers\Corpus\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('corpus/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\Corpus\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('corpus', [
	'uses' => 'App\Http\Controllers\Corpus\ResourceController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('corpus/new', [
	'uses' => 'App\Http\Controllers\Corpus\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('corpus/new', [
	'uses' => 'App\Http\Controllers\Corpus\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('corpus/{id}', [
	'uses' => 'App\Http\Controllers\Corpus\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('corpus/listForSelect', [
	'uses' => 'App\Http\Controllers\Corpus\ResourceController@listForSelect',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('corpus/{id}/entries', [
	'uses' => 'App\Http\Controllers\Corpus\EntryController@entries',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sentence', [
	'uses' => 'App\Http\Controllers\Sentence\ResourceController@browse',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sentence/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Sentence\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('sentence/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Sentence\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sentence/new', [
	'uses' => 'App\Http\Controllers\Sentence\ResourceController@formSentenceNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sentence/{idSentence}', [
	'uses' => 'App\Http\Controllers\Sentence\ResourceController@sentence',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sentence/{id}/editForm', [
	'uses' => 'App\Http\Controllers\Sentence\ResourceController@editForm',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('sentence/new', [
	'uses' => 'App\Http\Controllers\Sentence\ResourceController@newSentence',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('sentence', [
	'uses' => 'App\Http\Controllers\Sentence\ResourceController@updateSentence',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('sentence/{idSentence}', [
	'uses' => 'App\Http\Controllers\Sentence\ResourceController@deleteSentence',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sentence/{id}/document', [
	'uses' => 'App\Http\Controllers\Sentence\DocumentController@document',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sentence/{id}/document/formNew', [
	'uses' => 'App\Http\Controllers\Sentence\DocumentController@documentFormNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sentence/{id}/document/grid', [
	'uses' => 'App\Http\Controllers\Sentence\DocumentController@documentGrid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('sentence/{id}/document/new', [
	'uses' => 'App\Http\Controllers\Sentence\DocumentController@documentNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('sentence/{id}/document/{idDocument}', [
	'uses' => 'App\Http\Controllers\Sentence\DocumentController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('main/auth0Callback', [
	'uses' => 'App\Http\Controllers\LoginController@auth0Callback',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('auth0Login', [
	'uses' => 'App\Http\Controllers\LoginController@auth0Login',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('login', [
	'uses' => 'App\Http\Controllers\LoginController@login',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('login-error', [
	'uses' => 'App\Http\Controllers\LoginController@loginError',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('logout', [
	'uses' => 'App\Http\Controllers\LoginController@logout',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('impersonating', [
	'uses' => 'App\Http\Controllers\LoginController@impersonating',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('impersonating', [
	'uses' => 'App\Http\Controllers\LoginController@impersonatingPost',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('domain', [
	'uses' => 'App\Http\Controllers\Domain\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('domain/new', [
	'uses' => 'App\Http\Controllers\Domain\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('domain/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Domain\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('domain/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Domain\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('domain/{id}/edit', [
	'uses' => 'App\Http\Controllers\Domain\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('domain/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\Domain\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('domain', [
	'uses' => 'App\Http\Controllers\Domain\ResourceController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('domain/new', [
	'uses' => 'App\Http\Controllers\Domain\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('domain/{id}', [
	'uses' => 'App\Http\Controllers\Domain\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('domain/{id}/entries', [
	'uses' => 'App\Http\Controllers\Domain\EntryController@entries',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset', [
	'uses' => 'App\Http\Controllers\Dataset\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset/new', [
	'uses' => 'App\Http\Controllers\Dataset\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Dataset\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('dataset/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Dataset\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset/{id}/edit', [
	'uses' => 'App\Http\Controllers\Dataset\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\Dataset\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('dataset', [
	'uses' => 'App\Http\Controllers\Dataset\ResourceController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('dataset/new', [
	'uses' => 'App\Http\Controllers\Dataset\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('dataset/{id}', [
	'uses' => 'App\Http\Controllers\Dataset\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset/{id}/projects', [
	'uses' => 'App\Http\Controllers\Dataset\ProjectController@projects',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset/{id}/projects/formNew', [
	'uses' => 'App\Http\Controllers\Dataset\ProjectController@projectsFormNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset/{id}/projects/grid', [
	'uses' => 'App\Http\Controllers\Dataset\ProjectController@projectsGrid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('dataset/{id}/projects/new', [
	'uses' => 'App\Http\Controllers\Dataset\ProjectController@projectsNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('dataset/{id}/projects/{idProject}', [
	'uses' => 'App\Http\Controllers\Dataset\ProjectController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset/{id}/corpus', [
	'uses' => 'App\Http\Controllers\Dataset\CorpusController@corpus',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset/{id}/corpus/formNew', [
	'uses' => 'App\Http\Controllers\Dataset\CorpusController@corpusFormNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('dataset/{id}/corpus/grid', [
	'uses' => 'App\Http\Controllers\Dataset\CorpusController@corpusGrid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('dataset/{id}/corpus/new', [
	'uses' => 'App\Http\Controllers\Dataset\CorpusController@corpusNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('dataset/{id}/corpus/{idCorpus}', [
	'uses' => 'App\Http\Controllers\Dataset\CorpusController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationgroup', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@browse',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationgroup/new', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@new',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('relationgroup', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@newRelationGroup',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('relationgroup/grid', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@grid',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationgroup/listForSelect', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@listForSelect',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('relationgroup/listForTree', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@listForTree',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationgroup/{id}', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@edit',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationgroup/{id}/main', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@edit',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationgroup/{id}/entries', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@formEntries',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationgroup/{id}/rts', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@rts',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationgroup/{id}/rts/formNew', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@formNewRT',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationgroup/{id}/rts/grid', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@gridRT',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('relationgroup/{id}', [
	'uses' => 'App\Http\Controllers\RelationGroup\RelationGroupController@delete',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('task', [
	'uses' => 'App\Http\Controllers\Task\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('task/new', [
	'uses' => 'App\Http\Controllers\Task\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('task/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Task\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('task/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Task\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('task/{id}/edit', [
	'uses' => 'App\Http\Controllers\Task\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('task/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\Task\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('task', [
	'uses' => 'App\Http\Controllers\Task\ResourceController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('task/new', [
	'uses' => 'App\Http\Controllers\Task\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('task/{id}', [
	'uses' => 'App\Http\Controllers\Task\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('task/{id}/users', [
	'uses' => 'App\Http\Controllers\Task\UserController@users',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('task/{id}/users/formNew', [
	'uses' => 'App\Http\Controllers\Task\UserController@usersFormNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('task/{id}/users/grid', [
	'uses' => 'App\Http\Controllers\Task\UserController@usersGrid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('task/{id}/users/new', [
	'uses' => 'App\Http\Controllers\Task\UserController@projectsNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('task/{id}/users/{idUserTask}', [
	'uses' => 'App\Http\Controllers\Task\UserController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('usertask/{id}/edit', [
	'uses' => 'App\Http\Controllers\Task\UserTaskController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('usertask/{id}/documents', [
	'uses' => 'App\Http\Controllers\Task\UserTaskController@documents',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('usertask/{id}/documents/formNew', [
	'uses' => 'App\Http\Controllers\Task\UserTaskController@documentsNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('usertask/{id}/documents/grid', [
	'uses' => 'App\Http\Controllers\Task\UserTaskController@documentsGrid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('usertask/documents/new', [
	'uses' => 'App\Http\Controllers\Task\UserTaskController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('usertask/{idUserTask}/documents/{idUserTaskDocument}', [
	'uses' => 'App\Http\Controllers\Task\UserTaskController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('project', [
	'uses' => 'App\Http\Controllers\Project\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('project/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Project\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('project/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Project\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('project/new', [
	'uses' => 'App\Http\Controllers\Project\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('project/{id}/edit', [
	'uses' => 'App\Http\Controllers\Project\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('project/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\Project\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('project', [
	'uses' => 'App\Http\Controllers\Project\ResourceController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('project/new', [
	'uses' => 'App\Http\Controllers\Project\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('project/{id}', [
	'uses' => 'App\Http\Controllers\Project\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('project/{id}/users', [
	'uses' => 'App\Http\Controllers\Project\UserController@users',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('project/{id}/users/formNew', [
	'uses' => 'App\Http\Controllers\Project\UserController@usersFormNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('project/{id}/users/grid', [
	'uses' => 'App\Http\Controllers\Project\UserController@usersGrid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('project/{id}/users/new', [
	'uses' => 'App\Http\Controllers\Project\UserController@projectsNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('project/{id}/users/{idUser}', [
	'uses' => 'App\Http\Controllers\Project\UserController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sandbox/tree', [
	'uses' => 'App\Http\Controllers\SandboxController@tree',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('sandbox/tree/grid', [
	'uses' => 'App\Http\Controllers\SandboxController@grid',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sandbox/tree/domain/{idDomain}', [
	'uses' => 'App\Http\Controllers\SandboxController@getFramesByDomain',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sandbox/tree/type/{idType}', [
	'uses' => 'App\Http\Controllers\SandboxController@getFramesByType',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('sandbox/tree/frame/{idFrame}', [
	'uses' => 'App\Http\Controllers\SandboxController@getFELU',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('video', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('video/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('video/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('video/{id}/edit', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('video/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('video', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('video/{id}/formUpload', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@formUpload',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('video/upload', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@upload',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('video/new', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('video/new', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('video/{id}', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('video/listForSelect', [
	'uses' => 'App\Http\Controllers\Video\ResourceController@listForSelect',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('video/{id}/document', [
	'uses' => 'App\Http\Controllers\Video\DocumentController@corpus',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('video/{id}/document/formNew', [
	'uses' => 'App\Http\Controllers\Video\DocumentController@documentFormNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('video/{id}/document/grid', [
	'uses' => 'App\Http\Controllers\Video\DocumentController@documentGrid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('video/{id}/document/new', [
	'uses' => 'App\Http\Controllers\Video\DocumentController@documentNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('video/{id}/document/{idDocument}', [
	'uses' => 'App\Http\Controllers\Video\DocumentController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('grapher/domain', [
	'uses' => 'App\Http\Controllers\Grapher\DomainController@domain',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('grapher/domain/graph/{idEntity?}', [
	'uses' => 'App\Http\Controllers\Grapher\DomainController@domainGraph',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('grapher/framefe/graph/{idEntityRelation}', [
	'uses' => 'App\Http\Controllers\Grapher\DomainController@frameFeGraph',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('grapher/scenario', [
	'uses' => 'App\Http\Controllers\Grapher\ScenarioController@scenario',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('grapher/scenario/graph/{idEntity?}', [
	'uses' => 'App\Http\Controllers\Grapher\ScenarioController@scenarioGraph',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('grapher/frame', [
	'uses' => 'App\Http\Controllers\Grapher\FrameController@frame',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('grapher/frame/graph/{idEntity?}', [
	'uses' => 'App\Http\Controllers\Grapher\FrameController@frameGraph',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('grapher/framefe/graph/{idEntityRelation}', [
	'uses' => 'App\Http\Controllers\Grapher\FrameController@frameFeGraph',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('grapher/frame/report/{idEntityFrame}', [
	'uses' => 'App\Http\Controllers\Grapher\FrameController@frameReport',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('message/{id}', [
	'uses' => 'App\Http\Controllers\Message\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationtype', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@browse',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationtype/new', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@new',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('relationtype', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@newRelationType',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('relationtype/new', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@newRelationTypeMain',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('relationtype/grid', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@grid',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationtype/{id}/edit', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@edit',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationtype/{id}/main', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@main',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationtype/{id}/entries', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@formEntries',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('relationtype/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@formEdit',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('relationtype/{id}', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@update',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('relationtype/{id}', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@delete',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('relationtype/{id}/main', [
	'uses' => 'App\Http\Controllers\RelationType\RelationTypeController@deleteFromMain',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('network', [
	'uses' => 'App\Http\Controllers\Network\BrowseController@browse',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('network/grid', [
	'uses' => 'App\Http\Controllers\Network\BrowseController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('network/listForTree', [
	'uses' => 'App\Http\Controllers\Network\BrowseController@listForTree',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('layers', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@browse',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('layers/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('layers/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('layers/layergroup/new', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@formNewLemma',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('layers/layergroup/{idLemma}/lexemeentries', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@lexemeentries',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('layers/layergroup/{idLemma}/{fragment?}', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@lemma',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('layers/layergroup/new', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@newLemma',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('layers/layergroup/{idLemma}', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@updateLemma',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('layers/layergroup/{idLemma}', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@deleteLemma',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('layers/layer/new', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@formNewLexeme',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('layers/layer/{idLexeme}/wordforms', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@wordforms',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('layers/layer/{idLexeme}/{fragment?}', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@lexeme',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('layers/layer/new', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@newLexeme',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('layers/layer/{idLexeme}', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@updateLexeme',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('layers/layer/{idLexeme}', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@deleteLexeme',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('layers/layerentry/new', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@newLexemeEntry',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('layers/layerentries/{idLexemeEntry}', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@deleteLexemeEntry',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('layers/wordform/new', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@newWordform',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('layers/wordform/{idWordForm}', [
	'uses' => 'App\Http\Controllers\Layers\ResourceController@deleteWordform',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('image', [
	'uses' => 'App\Http\Controllers\Image\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('image/grid', [
	'uses' => 'App\Http\Controllers\Image\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('image/grid', [
	'uses' => 'App\Http\Controllers\Image\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('image/{id}/edit', [
	'uses' => 'App\Http\Controllers\Image\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('image/{id}/editForm', [
	'uses' => 'App\Http\Controllers\Image\ResourceController@editForm',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('image', [
	'uses' => 'App\Http\Controllers\Image\ResourceController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('image/new', [
	'uses' => 'App\Http\Controllers\Image\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('image/new', [
	'uses' => 'App\Http\Controllers\Image\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('image/{id}', [
	'uses' => 'App\Http\Controllers\Image\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('image/listForSelect', [
	'uses' => 'App\Http\Controllers\Image\ResourceController@listForSelect',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('image/{id}/document', [
	'uses' => 'App\Http\Controllers\Image\DocumentController@document',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('image/{id}/document/formNew', [
	'uses' => 'App\Http\Controllers\Image\DocumentController@documentFormNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('image/{id}/document/grid', [
	'uses' => 'App\Http\Controllers\Image\DocumentController@documentGrid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('image/{id}/document/new', [
	'uses' => 'App\Http\Controllers\Image\DocumentController@documentNew',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('image/{id}/document/{idDocument}', [
	'uses' => 'App\Http\Controllers\Image\DocumentController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('user', [
	'uses' => 'App\Http\Controllers\User\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('user/new', [
	'uses' => 'App\Http\Controllers\User\ResourceController@new',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('user/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\User\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('user/grid/{fragment?}', [
	'uses' => 'App\Http\Controllers\User\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('user/{id}/edit', [
	'uses' => 'App\Http\Controllers\User\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('user/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\User\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('user/{id}/authorize', [
	'uses' => 'App\Http\Controllers\User\ResourceController@authorizeUser',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('user/{id}/deauthorize', [
	'uses' => 'App\Http\Controllers\User\ResourceController@deauthorizeUser',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('user', [
	'uses' => 'App\Http\Controllers\User\ResourceController@update',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('user/new', [
	'uses' => 'App\Http\Controllers\User\ResourceController@create',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('user/{id}', [
	'uses' => 'App\Http\Controllers\User\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('user/listForSelect', [
	'uses' => 'App\Http\Controllers\User\ResourceController@listForSelect',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fe', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@browse',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/fe/grid', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fe/grid/{idDocument}/sentences', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@documentSentences',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fe/sentence/{idDocumentSentence}', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@sentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fe/annotations/{idSentence}', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@annotations',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fe/as/{idAS}/{token}', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@annotationSet',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fe/lus/{idDocumentSentence}/{idWord}', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@getLUs',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/fe/annotate', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@annotate',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/fe/frameElement', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@deleteFE',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/fe/create', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@createAS',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/fe/annotationset/{idAnnotationSet}', [
	'uses' => 'App\Http\Controllers\Annotation\FEController@deleteAS',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@browse',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/deixis/grid', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis/objectFE/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@objectFE',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@annotation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/deixis/formObject', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@formObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis/formObject/{idDynamicObject}/{order}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@getFormObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis/gridObjects/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@objectsForGrid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/deixis/updateObject', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@updateObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/deixis/updateObjectAnnotation', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@updateObjectAnnotation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/deixis/cloneObject', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@cloneObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/deixis/{idDynamicObject}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@deleteObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/deixis/updateBBox', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@updateBBox',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis/fes/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@feCombobox',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis/sentences/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@gridSentences',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/deixis/comment', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@annotationComment',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis/buildSentences/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@buildSentences',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis/formSentence/{idDocument}/{idSentence}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@formSentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/deixis/formSentence', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@sentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis/words/{idVideo}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@words',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/deixis/joinWords', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@joinWords',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/deixis/buildSentences/sentences/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@buildSentenceSentences',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/deixis/splitSentence', [
	'uses' => 'App\Http\Controllers\Annotation\DeixisController@splitSentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticFrameMode1', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode1Controller@browse',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/grid/staticFrameMode1', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode1Controller@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticFrameMode1/listForTree', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode1Controller@listForTree',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticFrameMode1/sentence/{idStaticSentenceMM}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode1Controller@annotationSentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticFrameMode1/sentence/{idSentenceMM}/object', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode1Controller@annotationSentenceObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticFrameMode1/fes', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode1Controller@annotationSentenceFes',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('annotation/staticFrameMode1/fes/{idStaticSentenceMM}/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode1Controller@annotationSentenceFesSubmit',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/staticFrameMode1/fes/{idStaticSentenceMM}/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode1Controller@annotationSentenceFesDelete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticEvent', [
	'uses' => 'App\Http\Controllers\Annotation\StaticEventController@browse',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticEvent/grid', [
	'uses' => 'App\Http\Controllers\Annotation\StaticEventController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticEvent/grid/{idDocument}/sentences', [
	'uses' => 'App\Http\Controllers\Annotation\StaticEventController@documentSentences',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticEvent/sentence/{idDocumentSentence}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticEventController@annotationSentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticEvent/addFrame', [
	'uses' => 'App\Http\Controllers\Annotation\StaticEventController@annotationSentenceFes',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('annotation/staticEvent/fes/{idDocumentSentence}/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticEventController@annotationSentenceFesSubmit',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/staticEvent/fes/{idDocumentSentence}/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticEventController@annotationSentenceFesDelete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticEvent/comment', [
	'uses' => 'App\Http\Controllers\Annotation\StaticEventController@annotationComment',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@browse',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/grid', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode/objectFE/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@objectFE',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@annotation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/formObject', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@formObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode/formObject/{idDynamicObject}/{order}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@getFormObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode/gridObjects/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@objectsForGrid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/updateObject', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@updateObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/updateObjectAnnotation', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@updateObjectAnnotation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/cloneObject', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@cloneObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/dynamicMode/{idDynamicObject}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@deleteObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/updateBBox', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@updateBBox',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/createBBox', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@createBBox',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode/fes/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@feCombobox',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode/sentences/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@gridSentences',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/comment', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@annotationComment',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode/buildSentences/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@buildSentences',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode/formSentence/{idDocument}/{idSentence}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@formSentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/formSentence', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@sentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode/words/{idVideo}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@words',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/joinWords', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@joinWords',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/dynamicMode/buildSentences/sentences/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@buildSentenceSentences',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/dynamicMode/splitSentence', [
	'uses' => 'App\Http\Controllers\Annotation\DynamicModeController@splitSentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticBBox', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@browse',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticBBox/grid', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticBBox/objectFE/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@objectFE',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticBBox/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@annotation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticBBox/formObject', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@formObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticBBox/formObject/{idDynamicObject}/{order}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@getFormObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticBBox/gridObjects/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@objectsForGrid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticBBox/updateObject', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@updateObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticBBox/updateObjectAnnotation', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@updateObjectAnnotation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticBBox/cloneObject', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@cloneObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/staticBBox/{idStaticObject}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@deleteObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticBBox/updateBBox', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@updateBBox',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticBBox/fes/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@feCombobox',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticBBox/sentences/{idDocument}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@gridSentences',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticBBox/comment', [
	'uses' => 'App\Http\Controllers\Annotation\StaticBBoxController@annotationComment',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticFrameMode2', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode2Controller@browse',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/grid/staticFrameMode2', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode2Controller@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticFrameMode2/listForTree', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode2Controller@listForTree',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticFrameMode2/sentence/{idStaticSentenceMM}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode2Controller@annotationSentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/staticFrameMode2/sentence/{idSentenceMM}/object', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode2Controller@annotationSentenceObject',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/staticFrameMode2/fes', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode2Controller@annotationSentenceFes',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('annotation/staticFrameMode2/fes/{idStaticSentenceMM}/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode2Controller@annotationSentenceFesSubmit',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/staticFrameMode2/fes/{idStaticSentenceMM}/{idFrame}', [
	'uses' => 'App\Http\Controllers\Annotation\StaticFrameMode2Controller@annotationSentenceFesDelete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fullText/{idDocument?}', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@browse',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/fullText/grid/{idDocument?}', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fullText/grid/{idDocument}/sentences', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@documentSentences',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fullText/sentence/{idDocumentSentence}', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@sentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fullText/annotations/{idSentence}', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@annotations',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fullText/spans/{idAS}', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@getSpans',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fullText/as/{idAS}/{token}', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@annotationSet',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/fullText/lus/{idDocumentSentence}/{idWord}', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@getLUs',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/fullText/annotate', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@annotate',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/fullText/label', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@deleteFE',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/fullText/create', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@createAS',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/fullText/annotationset/{idAnnotationSet}', [
	'uses' => 'App\Http\Controllers\Annotation\FullTextController@deleteAS',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/corpus', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@browse',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/corpus/grid', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/corpus/listForTree', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@listForTree',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/corpus/sentence/{idSentence}', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@annotationSentence',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/corpus/lus/{idSentence}/{idWord}', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@getLUs',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('annotation/corpus/sentence/{idSentence}/data', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@annotationSentenceData',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('annotation/corpus/label', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@saveLabel',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('annotation/corpus/ni', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@saveNI',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/corpus/label', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@deleteLabel',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('annotation/corpus/createAnnotationSet', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@createAnnotationSet',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/corpus/annotationSet', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@deleteAnnotationSet',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('annotation/corpus/annotationSet/lastFELayer', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@deleteLastFELayer',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('annotation/corpus/annotationSet/feLayer', [
	'uses' => 'App\Http\Controllers\Annotation\CorpusController@addFELayer',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('decisiontree', [
	'uses' => 'App\Http\Controllers\DecisionTree\BrowseController@browse',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('decisiontree/grid', [
	'uses' => 'App\Http\Controllers\DecisionTree\BrowseController@grid',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('decisiontree/frame/{entry}', [
	'uses' => 'App\Http\Controllers\DecisionTree\BrowseController@entry',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('fe', [
	'uses' => 'App\Http\Controllers\FE\ResourceController@newFE',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/{id}/edit', [
	'uses' => 'App\Http\Controllers\FE\ResourceController@edit',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/{id}/main', [
	'uses' => 'App\Http\Controllers\FE\ResourceController@main',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('fe/{id}', [
	'uses' => 'App\Http\Controllers\FE\ResourceController@delete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/{id}/formEdit', [
	'uses' => 'App\Http\Controllers\FE\ResourceController@formEdit',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->put('fe/{id}', [
	'uses' => 'App\Http\Controllers\FE\ResourceController@update',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/{id}/semanticTypes', [
	'uses' => 'App\Http\Controllers\FE\SemanticTypeController@semanticTypes',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/{id}/constraints', [
	'uses' => 'App\Http\Controllers\FE\ConstraintController@constraints',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/{id}/constraints/formNew', [
	'uses' => 'App\Http\Controllers\FE\ConstraintController@constraintsFormNew',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/{id}/constraints/grid', [
	'uses' => 'App\Http\Controllers\FE\ConstraintController@constraintsGrid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/{id}/entries', [
	'uses' => 'App\Http\Controllers\FE\EntryController@entries',
	'as' => NULL,
	'middleware' => ['master'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/relations/{idEntityRelation}/frame/{idFrameBase}', [
	'uses' => 'App\Http\Controllers\FE\RelationController@relations',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/relations/{idEntityRelation}/formNew', [
	'uses' => 'App\Http\Controllers\FE\RelationController@relationsFEFormNew',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('fe/relations/{idEntityRelation}/grid', [
	'uses' => 'App\Http\Controllers\FE\RelationController@gridRelationsFE',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@resource',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/grid', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@grid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{id}/subTypes', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@semanticTypes',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{id}/edit', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@get',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('semanticType/{idSemanticType}', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@masterDelete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/new', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@formNew',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('semanticType/new', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@new',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{idEntity}/childAdd/{root}', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@childFormAdd',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{idEntity}/childGrid', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@childGrid',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('semanticType/{idEntity}/add', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@childAdd',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('semanticType/relation/{idEntityRelation}', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@childDelete',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{idEntity}/childSubTypeAdd/{root}', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@childFormAddSubType',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('semanticType/{idEntity}/addSubType', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@childAddSubType',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('semanticType/{idEntity}/childSubTypeGrid', [
	'uses' => 'App\Http\Controllers\Qualia\ResourceController@childGridSubType',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/qualia', [
	'uses' => 'App\Http\Controllers\Qualia\ReportController@main',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/qualia/data', [
	'uses' => 'App\Http\Controllers\Qualia\ReportController@data',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('report/qualia/{idQualia?}/{lang?}', [
	'uses' => 'App\Http\Controllers\Qualia\ReportController@report',
	'as' => NULL,
	'middleware' => ['web'],
	'where' => [],
	'domain' => NULL,
]);

$router->get('empty', [
	'uses' => 'App\Http\Controllers\Controller@empty',
	'as' => NULL,
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);

$router->get('genre', [
	'uses' => 'App\Http\Controllers\Genre\GenreController@browse',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('genre/grid', [
	'uses' => 'App\Http\Controllers\Genre\GenreController@grid',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('genre/listForGrid', [
	'uses' => 'App\Http\Controllers\Genre\GenreController@listForGrid',
	'as' => NULL,
	'middleware' => ['admin'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('relation/fe/{idEntityRelation}', [
	'uses' => 'App\Http\Controllers\Relation\FEController@deleteFERelation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('relation/fe', [
	'uses' => 'App\Http\Controllers\Relation\FEController@newFERelation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('relation/feinternal/{idEntityRelation}', [
	'uses' => 'App\Http\Controllers\Relation\FEInternalController@deleteFERelation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('relation/feinternal', [
	'uses' => 'App\Http\Controllers\Relation\FEInternalController@newFERelation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->delete('relation/frame/{idEntityRelation}', [
	'uses' => 'App\Http\Controllers\Relation\FrameController@deleteFrameRelation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

$router->post('relation/frame', [
	'uses' => 'App\Http\Controllers\Relation\FrameController@newFrameRelation',
	'as' => NULL,
	'middleware' => ['auth'],
	'where' => [],
	'domain' => NULL,
]);

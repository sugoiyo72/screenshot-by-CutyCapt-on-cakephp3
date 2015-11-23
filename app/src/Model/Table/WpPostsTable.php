<?php
namespace App\Model\Table;

use App\Model\Entity\WpPost;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * WpPosts Model
 *
 */
class WpPostsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('wp_posts');
        $this->displayField('ID');
        $this->primaryKey('ID');

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('ID', 'create');

        $validator
            ->requirePresence('post_author', 'create')
            ->notEmpty('post_author');

        $validator
            ->add('post_date', 'valid', ['rule' => 'datetime'])
            ->requirePresence('post_date', 'create')
            ->notEmpty('post_date');

        $validator
            ->add('post_date_gmt', 'valid', ['rule' => 'datetime'])
            ->requirePresence('post_date_gmt', 'create')
            ->notEmpty('post_date_gmt');

        $validator
            ->requirePresence('post_content', 'create')
            ->notEmpty('post_content');

        $validator
            ->requirePresence('post_title', 'create')
            ->notEmpty('post_title');

        $validator
            ->requirePresence('post_excerpt', 'create')
            ->notEmpty('post_excerpt');

        $validator
            ->requirePresence('post_status', 'create')
            ->notEmpty('post_status');

        $validator
            ->requirePresence('comment_status', 'create')
            ->notEmpty('comment_status');

        $validator
            ->requirePresence('ping_status', 'create')
            ->notEmpty('ping_status');

        $validator
            ->requirePresence('post_password', 'create')
            ->notEmpty('post_password');

        $validator
            ->requirePresence('post_name', 'create')
            ->notEmpty('post_name');

        $validator
            ->requirePresence('to_ping', 'create')
            ->notEmpty('to_ping');

        $validator
            ->requirePresence('pinged', 'create')
            ->notEmpty('pinged');

        $validator
            ->add('post_modified', 'valid', ['rule' => 'datetime'])
            ->requirePresence('post_modified', 'create')
            ->notEmpty('post_modified');

        $validator
            ->add('post_modified_gmt', 'valid', ['rule' => 'datetime'])
            ->requirePresence('post_modified_gmt', 'create')
            ->notEmpty('post_modified_gmt');

        $validator
            ->requirePresence('post_content_filtered', 'create')
            ->notEmpty('post_content_filtered');

        $validator
            ->requirePresence('post_parent', 'create')
            ->notEmpty('post_parent');

        $validator
            ->requirePresence('guid', 'create')
            ->notEmpty('guid');

        $validator
            ->add('menu_order', 'valid', ['rule' => 'numeric'])
            ->requirePresence('menu_order', 'create')
            ->notEmpty('menu_order');

        $validator
            ->requirePresence('post_type', 'create')
            ->notEmpty('post_type');

        $validator
            ->requirePresence('post_mime_type', 'create')
            ->notEmpty('post_mime_type');

        $validator
            ->requirePresence('comment_count', 'create')
            ->notEmpty('comment_count');

        return $validator;
    }
}

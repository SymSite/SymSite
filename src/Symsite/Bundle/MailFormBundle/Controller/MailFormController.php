<?php

namespace Symsite\Bundle\MailFormBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MailFormController extends Controller
{
    /**
     * @Route("/mailform/send", name="mailform_send")
     * @Method("POST")
     */
    public function sendAction(Request $request)
    {
        $errors = $this->validate($request);

        if (count($errors) === 0) {
            $datas = $request->request->all();

            $body_datas = [];
            foreach($datas as $key => $value) {
                if ($key[0] != '_') {
                    $body_datas[$key] = $value;
                }
            }

            $body = $this->render('SymsiteMailFormBundle::mailform.text.twig', ['datas' => $body_datas]);

            $message = \Swift_Message::newInstance()
              ->setSubject($datas['_mailform_subject'])
              ->setFrom([$datas['email'] => $datas['name']])
              ->setTo($this->getParameter('symsite_mail_form.delivery_to'))
              ->setReplyTo([$datas['email'] => $datas['name']])
              ->setBody($body->getContent());
            $this->get('mailer')->send($message);

            $this->get('session')->getFlashBag()
              ->add('success', $this->get('translator')
              ->trans('Your message has been sent.'));
        } else {
            foreach ($errors as $error) {
                $this->get('session')->getFlashBag()->add('danger', $error);
            }
        }

        return $this->redirect($request->headers->get('referer'));
    }

    private function validate(Request $request)
    {
        $errors = [];

        $t = $this->get('translator');
        $csrf_man = $this->get('security.csrf.token_manager');

        $csrf_token = $csrf_man->getToken('mailform', $request->get('_token'));
        if (!$csrf_man->isTokenValid($csrf_token)) {
            $errors[] = $t->trans('The CSRF token is invalid.', [], 'validators');
        }

        if (!$request->get('_mailform_subject')) {
            $errors[] = $t->trans(
              '%attribute% is required.',
              ['%attribute%' => '_mailform_subject'],
              'validators'
            );
        }

        if (!$request->get('name')) {
            $errors[] = $t->trans(
              '%attribute% is required.',
              ['%attribute%' => $t->trans('contact.name')],
              'validators'
            );
        }

        if (!$request->get('email')) {
            $errors[] = $t->trans(
              '%attribute% is required.',
              ['%attribute%' => $t->trans('contact.email')],
              'validators'
            );
        }

        return $errors;
    }
}

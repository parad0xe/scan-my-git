import React, { useState } from "react";
import Recaptcha from "react-recaptcha";
import Button from "../Button";
import Title from "../../Widgets/Title";
import { Formik, Field, Form } from "formik";
import * as yup from "yup";
import axios from "axios";

interface MyFormValues {
  githubUrl: string;
}

const validationSchema = yup.object().shape({
  githubUrl: yup
    .string()
    .matches(
      /https?:\/\/(www.)?[-a-zA-Z0-9@:%.+~#=]{1,256}.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%+.~#?&\/\/=]*)/,
      "Merci d'entrer une URL github valide"
    )
    .required("Merci d'entrer une URL github"),
});

const QuickAnalysisForm: React.FC = () => {
  const initialValues: MyFormValues = { githubUrl: "" };
  const [isVerified, setIsVerified] = useState(false);
  const verifyCallback = () => {
    setIsVerified(!isVerified);
  };
  const callback = () => {
    return;
  };
  return (
    <>
      <div className="p-4 text-left mt-12">
        <div className="text-center">
          <Title name="Analyse Rapide" />
        </div>
        <Formik
          initialValues={initialValues}
          validationSchema={validationSchema}
          onSubmit={(values, { setSubmitting, resetForm }) => {
            setTimeout(async () => {
              const { githubUrl } = values;
              console.log(githubUrl);
              await axios.post("/context/quick-analysis", {
                githubUrl,
              });
              resetForm();
              setSubmitting(false);
            }, 1000);
          }}
        >
          {({
            errors,
            touched,
            handleChange,
            handleBlur,
            handleSubmit,
            isSubmitting,
          }) => (
            <Form className="flex w-full" onSubmit={handleSubmit}>
              <div className="w-4/5">
                <Field
                  placeholder="Github URL"
                  className="form-input mt-1 block h-8 placeholder-opacity-5 w-11/12 px-4"
                  type="text"
                  id="githubUrl"
                  name="githubUrl"
                  touched={touched}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  disabled={isSubmitting}
                />
                {errors.githubUrl && touched.githubUrl ? (
                  <div className="text-red-600 m-2 text-xs">
                    {errors.githubUrl}
                  </div>
                ) : null}
              </div>
              <div className="w-1/5">
                <Button
                  name="Analyse rapide"
                  classes="justify-center py-2 px-4 border text-white w-full"
                  isVerified={isVerified}
                  isSubmitting={isSubmitting}
                />
              </div>
            </Form>
          )}
        </Formik>
        <Recaptcha
          sitekey="6Le7IL8bAAAAAELg75XwkJTOLJww822VXPx6GPky"
          render="explicit"
          verifyCallback={verifyCallback}
          onloadCallback={callback}
        />
      </div>
    </>
  );
};

export default QuickAnalysisForm;
